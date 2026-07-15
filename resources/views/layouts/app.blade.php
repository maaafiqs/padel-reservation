<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maaafiqs Padel - @yield('title', 'Reservasi Lapangan Padel Modern')</title>
    <meta name="description" content="Platform reservasi lapangan padel terbaik, termudah dan tercepat.">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
</head>
<body>
    
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container nav-container">
            <div class="flex items-center gap-4">
                @if(request()->routeIs('user.*'))
                <button id="sidebar-toggle" class="btn btn-outline" style="padding: 0.5rem 0.75rem; border: none; font-size: 1.25rem;" title="Toggle Sidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>
                @endif
                <a href="{{ route('home') }}" class="logo">
                    <i class="fa-solid fa-table-tennis-paddle-ball"></i> Maaafiqs Padel
                </a>
            </div>
            
            @if(!request()->routeIs('user.*'))
            <!-- Mobile Main Nav Toggle -->
            <button id="mobile-nav-btn" class="mobile-nav-toggle">
                <i class="fa-solid fa-bars"></i>
            </button>
            @endif
            
            <div class="nav-links">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                <a href="#lapangan" class="nav-link">Lapangan</a>
                <a href="#fasilitas" class="nav-link">Fasilitas</a>
                <a href="#coach" class="nav-link">Pelatih</a>
                <a href="#galeri" class="nav-link">Galeri</a>
            </div>

            <div class="nav-auth">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">Dashboard Admin</a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="btn btn-outline">Dashboard Saya</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    @if(!request()->routeIs('user.*'))
    <footer style="background-color: var(--surface); border-top: 1px solid var(--border); padding: 4rem 0 2rem; margin-top: 4rem;">
        <div class="container grid grid-cols-3 gap-8" style="margin-bottom: 2rem;">
            <div>
                <a href="{{ route('home') }}" class="logo mb-4">
                    <i class="fa-solid fa-table-tennis-paddle-ball"></i> Maaafiqs Padel
                </a>
                <p class="text-muted">Platform reservasi lapangan padel terbaik dengan fasilitas lengkap dan pelatih profesional.</p>
            </div>
            <div>
                <h4 class="mb-4">Tautan</h4>
                <ul class="flex flex-col gap-2 text-muted">
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><a href="#lapangan">Lapangan</a></li>
                    <li><a href="#fasilitas">Fasilitas</a></li>
                    <li><a href="#galeri">Galeri</a></li>
                </ul>
            </div>
            <div>
                <h4 class="mb-4">Kontak</h4>
                <ul class="flex flex-col gap-2 text-muted">
                    <li><i class="fa-solid fa-phone mr-2"></i> +62 812 3456 7890</li>
                    <li><i class="fa-solid fa-envelope mr-2"></i> info@maaafiqspadel.com</li>
                    <li><i class="fa-solid fa-location-dot mr-2"></i> Jl. Padel Indah No. 1, Jakarta</li>
                </ul>
            </div>
        </div>
        <div class="container text-center text-muted" style="border-top: 1px solid var(--border); padding-top: 2rem;">
            &copy; {{ date('Y') }} Maaafiqs Padel. All rights reserved.
        </div>
    </footer>
    @endif

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Animate On Scroll
            AOS.init({
                duration: 800,
                once: true,
                offset: 50,
            });

            const toggleBtn = document.getElementById('sidebar-toggle');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    const layout = document.querySelector('.dashboard-layout');
                    if(layout) layout.classList.toggle('sidebar-collapsed');
                });
            }

            const mobileNavBtn = document.getElementById('mobile-nav-btn');
            if (mobileNavBtn) {
                mobileNavBtn.addEventListener('click', function() {
                    const navbar = document.querySelector('.navbar');
                    if(navbar) navbar.classList.toggle('mobile-open');
                });
            }
        });
    </script>
</body>
</html>
