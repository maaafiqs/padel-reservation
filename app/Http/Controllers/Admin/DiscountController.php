<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::all();

        return view('admin.discounts.index', compact('discounts'));
    }

    public function create()
    {
        return view('admin.discounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:discounts',
            'type' => 'required|in:percentage,nominal',
            'percentage' => 'nullable|required_if:type,percentage|numeric|min:0|max:100',
            'nominal_amount' => 'nullable|required_if:type,nominal|numeric|min:0',
            'valid_until' => 'nullable|date',
            'is_active' => 'required|boolean',
        ]);

        Discount::create($validated);

        return redirect()->route('admin.discounts.index')->with('success', 'Diskon berhasil ditambahkan.');
    }

    public function edit(Discount $discount)
    {
        return view('admin.discounts.edit', compact('discount'));
    }

    public function update(Request $request, Discount $discount)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:discounts,code,'.$discount->id,
            'type' => 'required|in:percentage,nominal',
            'percentage' => 'nullable|required_if:type,percentage|numeric|min:0|max:100',
            'nominal_amount' => 'nullable|required_if:type,nominal|numeric|min:0',
            'valid_until' => 'nullable|date',
            'is_active' => 'required|boolean',
        ]);

        $discount->update($validated);

        return redirect()->route('admin.discounts.index')->with('success', 'Diskon berhasil diperbarui.');
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();

        return redirect()->route('admin.discounts.index')->with('success', 'Diskon berhasil dihapus.');
    }
}
