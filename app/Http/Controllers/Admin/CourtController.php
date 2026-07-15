<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Court;
use Illuminate\Http\Request;

class CourtController extends Controller
{
    public function index()
    {
        $courts = Court::all();
        return view('admin.courts.index', compact('courts'));
    }

    public function create()
    {
        return view('admin.courts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:Indoor,Outdoor',
            'price_per_hour' => 'required|numeric|min:0',
            'status' => 'required|in:available,maintenance',
        ]);

        Court::create($validated);
        return redirect()->route('admin.courts.index')->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function edit(Court $court)
    {
        return view('admin.courts.edit', compact('court'));
    }

    public function update(Request $request, Court $court)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:Indoor,Outdoor',
            'price_per_hour' => 'required|numeric|min:0',
            'status' => 'required|in:available,maintenance',
        ]);

        $court->update($validated);
        return redirect()->route('admin.courts.index')->with('success', 'Lapangan berhasil diperbarui.');
    }

    public function destroy(Court $court)
    {
        $court->delete();
        return redirect()->route('admin.courts.index')->with('success', 'Lapangan berhasil dihapus.');
    }
}
