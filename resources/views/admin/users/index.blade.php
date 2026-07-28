@extends('layouts.admin')

@section('title', __('Manage Users'))

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl">{{ __('Manage Users') }}</h1>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus mr-2"></i> {{ __('Add Admin') }}</a>
</div>

<div class="card mb-6" style="background-color: var(--background); border: 1px solid var(--border); box-shadow: none;">
    <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
        <div class="form-group mb-0" style="flex: 1; min-width: 250px;">
            <label class="form-label text-sm">{{ __('Search (Name or Email)') }}</label>
            <input type="text" name="search" class="form-input" placeholder="{{ __('Enter keyword...') }}" value="{{ request('search') }}">
        </div>
        <div class="form-group mb-0" style="width: 200px;">
            <label class="form-label text-sm">{{ __('User Role') }}</label>
            <select name="role" class="form-input">
                <option value="">{{ __('All Roles') }}</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass mr-2"></i> {{ __('Search') }}</button>
            @if(request('search') || request('role'))
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline">{{ __('Reset') }}</a>
            @endif
        </div>
    </form>
</div>

@if(session('error'))
    <div class="badge badge-warning mb-6" style="padding: 1rem; width: 100%; display: flex; font-size: 1rem; border-radius: var(--radius-md); background-color: #fee2e2; color: #991b1b;">
        <i class="fa-solid fa-triangle-exclamation mr-2"></i> {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div class="badge badge-success mb-6" style="padding: 1rem; width: 100%; display: flex; font-size: 1rem; border-radius: var(--radius-md); background-color: #dcfce7; color: #166534;">
        <i class="fa-solid fa-check mr-2"></i> {{ session('success') }}
    </div>
@endif

<div class="card">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border);">
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">{{ __('Name') }}</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">{{ __('Email') }}</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">{{ __('Role') }}</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">{{ __('Registration Date') }}</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 1rem; font-weight: 500;">{{ $u->name }}</td>
                    <td style="padding: 1rem;">{{ $u->email }}</td>
                    <td style="padding: 1rem;">
                        @if($u->role === 'admin')
                            <span class="badge" style="background-color: var(--primary); color: white;">Admin</span>
                        @else
                            <span class="badge" style="background-color: var(--border); color: var(--text-main);">User</span>
                        @endif
                    </td>
                    <td style="padding: 1rem;">{{ $u->created_at->format('d M Y') }}</td>
                    <td style="padding: 1rem;">
                        <div class="flex gap-2">
                            @if($u->role === 'admin')
                                <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;"><i class="fa-solid fa-pen"></i> {{ __('Edit') }}</a>
                            @endif
                            <form action="{{ route('admin.users.destroy', $u) }}" method="POST" onsubmit="return confirm('{{ __('Delete this user?') }}');">
                                @csrf
                                @method('DELETE')
                                <button class="btn" style="padding: 0.25rem 0.75rem; font-size: 0.875rem; background-color: #fee2e2; color: #991b1b;"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 1rem; text-align: center; color: var(--text-muted);">{{ __('No user data yet.') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
