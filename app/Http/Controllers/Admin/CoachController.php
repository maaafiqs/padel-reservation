<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    public function index()
    {
        $coaches = Coach::all();
        return view('admin.coaches.index', compact('coaches'));
    }

    public function create()
    {
        return view('admin.coaches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'price_per_hour' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'is_available' => 'required|boolean',
            'bio' => 'nullable|string',
        ]);

        Coach::create($validated);
        return redirect()->route('admin.coaches.index')->with('success', 'Coach berhasil ditambahkan.');
    }

    public function edit(Coach $coach)
    {
        return view('admin.coaches.edit', compact('coach'));
    }

    public function update(Request $request, Coach $coach)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'price_per_hour' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'is_available' => 'required|boolean',
            'bio' => 'nullable|string',
        ]);

        $coach->update($validated);
        return redirect()->route('admin.coaches.index')->with('success', 'Coach berhasil diperbarui.');
    }

    public function destroy(Coach $coach)
    {
        $coach->delete();
        return redirect()->route('admin.coaches.index')->with('success', 'Coach berhasil dihapus.');
    }
}
