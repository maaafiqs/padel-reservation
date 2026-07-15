<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $query = Inventory::query();

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('item_code', 'like', "%{$search}%");
            });
        }

        if (request()->filled('type')) {
            if (request('type') === 'consumable') {
                $query->where('is_consumable', true);
            } elseif (request('type') === 'rental') {
                $query->where('is_consumable', false);
            }
        }

        $inventories = $query->orderBy('created_at', 'desc')->get();
        return view('admin.inventories.index', compact('inventories'));
    }

    public function create()
    {
        return view('admin.inventories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_code' => 'required|string|unique:inventories|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);
        
        $validated['is_consumable'] = $request->has('is_consumable');

        Inventory::create($validated);
        return redirect()->route('admin.inventories.index')->with('success', 'Barang inventaris berhasil ditambahkan.');
    }

    public function edit(Inventory $inventory)
    {
        return view('admin.inventories.edit', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'item_code' => 'required|string|max:255|unique:inventories,item_code,' . $inventory->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);
        
        $validated['is_consumable'] = $request->has('is_consumable');

        $inventory->update($validated);
        return redirect()->route('admin.inventories.index')->with('success', 'Barang inventaris berhasil diperbarui.');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('admin.inventories.index')->with('success', 'Barang inventaris berhasil dihapus.');
    }

    public function qrcode(Inventory $inventory)
    {
        // Data to be encoded in the QR code
        $qrData = json_encode([
            'kode' => $inventory->item_code,
            'nama' => $inventory->name,
            'harga' => $inventory->price,
        ]);

        return view('admin.inventories.qrcode', compact('inventory', 'qrData'));
    }
}
