<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->string('item_code')->nullable()->unique()->after('id');
        });

        // Backfill existing items
        $inventories = \App\Models\Inventory::all();
        $counter = 1;
        foreach ($inventories as $inv) {
            $inv->item_code = 'INV-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
            $inv->save();
            $counter++;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn('item_code');
        });
    }
};
