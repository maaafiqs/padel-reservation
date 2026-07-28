@extends('layouts.admin')

@section('title', __('Manage Courts'))

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl">{{ __('Manage Courts') }}</h1>
    <a href="{{ route('admin.courts.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus mr-2"></i> {{ __('Add Court') }}</a>
</div>

<div class="card">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border);">
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">{{ __('ID') }}</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">{{ __('Court Name') }}</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">{{ __('Type') }}</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">{{ __('Price/Hour') }}</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">{{ __('Status') }}</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courts as $court)
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 1rem;">{{ $court->id }}</td>
                    <td style="padding: 1rem; font-weight: 500;">{{ $court->name }}</td>
                    <td style="padding: 1rem;">{{ $court->type }}</td>
                    <td style="padding: 1rem;">Rp {{ number_format($court->price_per_hour, 0, ',', '.') }}</td>
                    <td style="padding: 1rem;">
                        @if($court->status === 'available')
                            <span class="badge badge-success">{{ __('Available') }}</span>
                        @else
                            <span class="badge badge-warning">{{ __('Maintenance') }}</span>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.courts.edit', $court) }}" class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;"><i class="fa-solid fa-pen"></i></a>
                            <form action="{{ route('admin.courts.destroy', $court) }}" method="POST" onsubmit="return confirm('{{ __('Delete this court?') }}');">
                                @csrf
                                @method('DELETE')
                                <button class="btn" style="padding: 0.25rem 0.75rem; font-size: 0.875rem; background-color: #fee2e2; color: #991b1b;"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 1rem; text-align: center; color: var(--text-muted);">{{ __('No court data yet.') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
