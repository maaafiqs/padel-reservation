@extends('layouts.app')

@section('title', __('My Profile'))

@section('content')
<div class="dashboard-layout">
    @include('user.partials.sidebar')

    <!-- Main Content -->
    <main class="dashboard-content">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl">{{ __('My Profile') }}</h1>
        </div>

        @if(session('success'))
            <div class="card mb-6 bg-primary-light" style="border-left: 4px solid var(--primary);">
                <p class="text-primary font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="card mb-6" style="background-color: #fee2e2; border-left: 4px solid #991b1b;">
                <ul style="color: #991b1b; padding-left: 1rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card" style="max-width: 600px;">
            <form action="{{ route('user.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group mb-6">
                    <label for="name" class="form-label">{{ __('Full Name') }}</label>
                    <input type="text" name="name" id="name" class="form-input" required value="{{ old('name', $user->name) }}">
                </div>

                <div class="form-group mb-6">
                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                    <input type="email" name="email" id="email" class="form-input" required value="{{ old('email', $user->email) }}">
                </div>

                <div class="form-group mb-6">
                    <label for="role" class="form-label">{{ __('Role') }}</label>
                    <input type="text" id="role" class="form-input" disabled value="{{ ucfirst($user->role) }}" style="background-color: var(--background); cursor: not-allowed;">
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save mr-2"></i> {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection

