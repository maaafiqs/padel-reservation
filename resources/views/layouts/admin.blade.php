<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Maaafiqs Padel - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    
    <!-- Topbar Admin -->
    <nav class="navbar" style="padding: 0.75rem 0;">
        <div class="container nav-container" style="max-width: 100%; padding: 0 2rem;">
            <div class="flex items-center gap-4">
                <button id="sidebar-toggle" class="btn btn-outline" style="padding: 0.5rem 0.75rem; border: none; font-size: 1.25rem;" title="Toggle Sidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <a href="{{ route('home') }}" class="logo">
                    <i class="fa-solid fa-table-tennis-paddle-ball"></i> Maaafiqs Padel <span class="text-sm text-muted font-normal ml-2">| Admin Panel</span>
                </a>
            </div>
            
            <div class="admin-auth" style="display: flex; gap: 0.5rem; align-items: center;">
                <div class="flex items-center gap-2" style="border-right: 1px solid var(--border); padding-right: 1rem; margin-right: 0.5rem;">
                    <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'font-bold' : '' }}" style="text-decoration: none; color: inherit;">EN</a>
                    <span>|</span>
                    <a href="{{ route('lang.switch', 'id') }}" class="{{ app()->getLocale() == 'id' ? 'font-bold' : '' }}" style="text-decoration: none; color: inherit;">ID</a>
                </div>
                <div class="flex items-center gap-2 mr-2">
                    <div style="width: 35px; height: 35px; border-radius: 50%; background-color: var(--primary-light); display: flex; align-items: center; justify-content: center; color: var(--primary); font-weight: bold;">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <span class="font-medium hidden-mobile">{{ explode(' ', auth()->user()->name)[0] }}</span>
                </div>
                <a href="{{ route('home') }}" class="btn btn-outline" style="padding: 0.5rem; display: flex; align-items: center; justify-content: center;" title="{{ __('To Home') }}">
                    <i class="fa-solid fa-home"></i><span class="hidden-mobile ml-2">{{ __('To Home') }}</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="padding: 0.5rem; display: flex; align-items: center; justify-content: center;" title="{{ __('Logout') }}">
                        <i class="fa-solid fa-sign-out-alt"></i><span class="hidden-mobile ml-2">{{ __('Logout') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h3 class="text-xl mb-6 text-primary">{{ __('Admin Panel') }}</h3>
            <nav class="sidebar-menu">
                            <div class="sidebar-label text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 px-3 mt-4">{{ __('Menu Utama') }}</div>
                            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="fa-solid fa-gauge"></i> {{ __('Dashboard') }}
                            </a>

                            <div class="sidebar-label text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 px-3 mt-4">{{ __('Manajemen') }}</div>
                            <a href="{{ route('admin.courts.index') }}" class="sidebar-link {{ request()->routeIs('admin.courts.*') ? 'active' : '' }}">
                                <i class="fa-solid fa-layer-group"></i> {{ __('Manage Courts') }}
                            </a>
                            <a href="{{ route('admin.reservations.index') }}" class="sidebar-link {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                                <i class="fa-solid fa-calendar-check"></i> {{ __('Reservations') }}
                            </a>
                            <a href="{{ route('admin.coaches.index') }}" class="sidebar-link {{ request()->routeIs('admin.coaches.*') ? 'active' : '' }}">
                                <i class="fa-solid fa-user-tie"></i> {{ __('Coaches') }}
                            </a>
                            <a href="{{ route('admin.inventories.index') }}" class="sidebar-link {{ request()->routeIs('admin.inventories.*') ? 'active' : '' }}">
                                <i class="fa-solid fa-box"></i> {{ __('Inventory') }}
                            </a>
                            <a href="{{ route('admin.announcements.index') }}" class="sidebar-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                                <i class="fa-solid fa-bullhorn"></i> {{ __('Announcements') }}
                            </a>
                            <a href="{{ route('admin.discounts.index') }}" class="sidebar-link {{ request()->routeIs('admin.discounts.*') ? 'active' : '' }}">
                                <i class="fa-solid fa-tags"></i> {{ __('Prices & Discounts') }}
                            </a>

                            <div class="sidebar-label text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 px-3 mt-4">{{ __('Pengaturan') }}</div>
                            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="fa-solid fa-users"></i> {{ __('Users') }}
                            </a>
                            <a href="{{ route('admin.backup.index') }}" class="sidebar-link {{ request()->routeIs('admin.backup.*') ? 'active' : '' }}">
                                <i class="fa-solid fa-database"></i> {{ __('Backup & Restore') }}
                            </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="dashboard-content">
            @if(session('success'))
                <div class="badge badge-success mb-6" style="padding: 1rem; width: 100%; display: flex; font-size: 1rem; border-radius: var(--radius-md);">
                    <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('sidebar-toggle');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    document.querySelector('.dashboard-layout').classList.toggle('sidebar-collapsed');
                });
            }
        });
    </script>
</body>
</html>
