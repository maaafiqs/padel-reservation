@extends('layouts.admin')

@section('title', 'Harga & Diskon')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl">Harga & Diskon</h1>
    <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus mr-2"></i> {{ __('Add Discount') }}</a>
</div>

<div class="card">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border);">
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Kode Diskon</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Potongan</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Berlaku Sampai</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">{{ __('Status') }}</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($discounts as $discount)
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 1rem; font-weight: 500;">{{ $discount->code }}</td>
                    <td style="padding: 1rem;">
                        @if($discount->type === 'nominal')
                            Rp {{ number_format($discount->nominal_amount, 0, ',', '.') }}
                        @else
                            {{ $discount->percentage }}%
                        @endif
                    </td>
                    <td style="padding: 1rem;">{{ $discount->valid_until ? \Carbon\Carbon::parse($discount->valid_until)->format('d M Y') : 'Tanpa Batas' }}</td>
                    <td style="padding: 1rem;">
                        @if($discount->is_active)
                            <span class="badge badge-success">{{ __('Active') }}</span>
                        @else
                            <span class="badge badge-warning">Non-Aktif</span>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.discounts.edit', $discount) }}" class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;"><i class="fa-solid fa-pen"></i></a>
                            <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" onsubmit="return confirm('{{ __('Delete this discount?') }}');">
                                @csrf
                                @method('DELETE')
                                <button class="btn" style="padding: 0.25rem 0.75rem; font-size: 0.875rem; background-color: #fee2e2; color: #991b1b;"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 1rem; text-align: center; color: var(--text-muted);">{{ __('No discount data yet.') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
