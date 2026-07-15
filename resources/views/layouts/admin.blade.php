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
                <div class="flex items-center gap-2 mr-2">
                    <div style="width: 35px; height: 35px; border-radius: 50%; background-color: var(--primary-light); display: flex; align-items: center; justify-content: center; color: var(--primary); font-weight: bold;">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <span class="font-medium hidden-mobile">{{ explode(' ', auth()->user()->name)[0] }}</span>
                </div>
                <a href="{{ route('home') }}" class="btn btn-outline" style="padding: 0.5rem; display: flex; align-items: center; justify-content: center;" title="Ke Beranda">
                    <i class="fa-solid fa-home"></i><span class="hidden-mobile ml-2">Ke Beranda</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="padding: 0.5rem; display: flex; align-items: center; justify-content: center;" title="Logout">
                        <i class="fa-solid fa-sign-out-alt"></i><span class="hidden-mobile ml-2">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h3 class="text-xl mb-6 text-primary">Admin Panel</h3>
            <nav class="sidebar-menu">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge"></i> Dashboard
                </a>
                <a href="{{ route('admin.courts.index') }}" class="sidebar-link {{ request()->routeIs('admin.courts.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-layer-group"></i> Kelola Lapangan
                </a>
                <a href="{{ route('admin.reservations.index') }}" class="sidebar-link {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-check"></i> Reservasi
                </a>
                <a href="{{ route('admin.coaches.index') }}" class="sidebar-link {{ request()->routeIs('admin.coaches.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-tie"></i> Coach
                </a>
                <a href="{{ route('admin.inventories.index') }}" class="sidebar-link {{ request()->routeIs('admin.inventories.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-box"></i> Inventaris
                </a>
                <a href="{{ route('admin.announcements.index') }}" class="sidebar-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-bullhorn"></i> Pengumuman
                </a>
                <a href="{{ route('admin.discounts.index') }}" class="sidebar-link {{ request()->routeIs('admin.discounts.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-tags"></i> Harga & Diskon
                </a>
                <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i> Pengguna
                </a>
                <a href="{{ route('admin.backup.index') }}" class="sidebar-link {{ request()->routeIs('admin.backup.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-database"></i> Backup & Restore
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
