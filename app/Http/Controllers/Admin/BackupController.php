<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BackupController extends Controller
{
    public function index()
    {
        $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' AND name NOT LIKE 'migrations';");
        $tableNames = array_map(function($t) { return $t->name; }, $tables);
        return view('admin.backup.index', compact('tableNames'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'format' => 'required|in:csv,sql,db',
            'table' => 'required_if:format,csv|string|nullable',
        ]);

        $format = $request->input('format');

        if ($format === 'db') {
            $dbPath = database_path('database.sqlite');
            if (!file_exists($dbPath)) {
                return redirect()->back()->with('error', 'File database tidak ditemukan.');
            }
            return response()->download($dbPath, 'backup-' . date('Y-m-d-His') . '.sqlite');
        }

        if ($format === 'sql') {
            $tables = DB::select("SELECT name, sql FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%';");
            
            $sqlContent = "-- Padel Reservation Database Backup\n";
            $sqlContent .= "-- Generated at: " . date('Y-m-d H:i:s') . "\n\n";
            $sqlContent .= "PRAGMA foreign_keys=OFF;\n\n";

            foreach ($tables as $table) {
                $tableName = $table->name;
                
                $sqlContent .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sqlContent .= $table->sql . ";\n\n";
                
                $rows = DB::table($tableName)->get();
                foreach ($rows as $row) {
                    $rowArray = (array)$row;
                    $columns = array_keys($rowArray);
                    $escapedColumns = array_map(function($col) { return "`{$col}`"; }, $columns);
                    
                    $values = array_values($rowArray);
                    $escapedValues = array_map(function($val) {
                        if (is_null($val)) return 'NULL';
                        return "'" . str_replace("'", "''", $val) . "'";
                    }, $values);
                    
                    $sqlContent .= "INSERT INTO `{$tableName}` (" . implode(', ', $escapedColumns) . ") VALUES (" . implode(', ', $escapedValues) . ");\n";
                }
                $sqlContent .= "\n";
            }
            
            $sqlContent .= "PRAGMA foreign_keys=ON;\n";
            
            $fileName = 'backup-' . date('Y-m-d-His') . '.sql';
            return response($sqlContent, 200, [
                'Content-Type' => 'application/sql',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]);
        }

        if ($format === 'csv') {
            $table = $request->input('table');
            if (!$table || !Schema::hasTable($table)) {
                return redirect()->back()->with('error', 'Tabel tidak valid untuk ekspor CSV.');
            }

            $rows = DB::table($table)->get();
            
            $csvContent = '';
            if ($rows->count() > 0) {
                // Headers
                $headers = array_keys((array)$rows->first());
                $csvContent .= implode(',', array_map(function($h) { return '"' . str_replace('"', '""', $h) . '"'; }, $headers)) . "\n";
                
                foreach ($rows as $row) {
                    $fields = array_values((array)$row);
                    $csvContent .= implode(',', array_map(function($f) {
                        if (is_null($f)) return '""';
                        return '"' . str_replace('"', '""', $f) . '"';
                    }, $fields)) . "\n";
                }
            } else {
                $columns = Schema::getColumnListing($table);
                $csvContent .= implode(',', array_map(function($h) { return '"' . str_replace('"', '""', $h) . '"'; }, $columns)) . "\n";
            }
            
            $fileName = $table . '-' . date('Y-m-d-His') . '.csv';
            return response($csvContent, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]);
        }

        return redirect()->back()->with('error', 'Format ekspor tidak didukung.');
    }

    public function restore(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'format' => 'required|in:csv,sql,db',
            'table' => 'required_if:format,csv|string|nullable',
            'confirm_overwrite' => 'required|accepted',
        ]);

        $format = $request->input('format');
        $uploadedFile = $request->file('file');

        // Backup current database first for rollback safety
        $dbPath = database_path('database.sqlite');
        $tempBackupPath = database_path('database.sqlite.bak');
        if (file_exists($dbPath)) {
            copy($dbPath, $tempBackupPath);
        }

        try {
            if ($format === 'db') {
                copy($uploadedFile->getRealPath(), $dbPath);
                
                DB::disconnect();
                DB::reconnect();
                DB::select("SELECT name FROM sqlite_master LIMIT 1;");
            } 
            
            elseif ($format === 'sql') {
                $sqlContent = file_get_contents($uploadedFile->getRealPath());
                
                DB::disconnect();
                DB::reconnect();
                DB::unprepared($sqlContent);
            } 
            
            elseif ($format === 'csv') {
                $table = $request->input('table');
                if (!$table || !Schema::hasTable($table)) {
                    throw new \Exception('Tabel tujuan ekspor CSV tidak ditemukan.');
                }

                DB::disconnect();
                DB::reconnect();
                
                DB::statement('PRAGMA foreign_keys = OFF;');
                
                // Clear table
                DB::table($table)->truncate();
                
                $fileStream = fopen($uploadedFile->getRealPath(), 'r');
                if ($fileStream) {
                    $headers = null;
                    while (($row = fgetcsv($fileStream)) !== FALSE) {
                        if (!$headers) {
                            $headers = $row;
                            continue;
                        }
                        
                        if (count($headers) !== count($row)) {
                            continue;
                        }
                        
                        $data = array_combine($headers, $row);
                        foreach ($data as $key => $val) {
                            if ($val === '') {
                                $data[$key] = null;
                            }
                        }
                        
                        DB::table($table)->insert($data);
                    }
                    fclose($fileStream);
                } else {
                    throw new \Exception('Gagal membuka file CSV.');
                }
                
                DB::statement('PRAGMA foreign_keys = ON;');
            }

            // Cleanup backup on success
            if (file_exists($tempBackupPath)) {
                unlink($tempBackupPath);
            }

            $message = 'Data berhasil dipulihkan (Restore Sukses).';
            if ($format === 'csv') {
                $message = "Tabel `{$request->input('table')}` berhasil dipulihkan dari file CSV.";
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            // Restore database back to previous state if anything failed
            if (file_exists($tempBackupPath)) {
                copy($tempBackupPath, $dbPath);
                unlink($tempBackupPath);
            }
            
            DB::disconnect();
            DB::reconnect();

            return redirect()->back()->with('error', 'Gagal memulihkan data: ' . $e->getMessage());
        }
    }
}
