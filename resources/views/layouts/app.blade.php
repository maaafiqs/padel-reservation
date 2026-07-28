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
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">{{ __('Home') }}</a>
                <a href="#lapangan" class="nav-link">{{ __('Courts') }}</a>
                <a href="#fasilitas" class="nav-link">{{ __('Facilities') }}</a>
                <a href="#coach" class="nav-link">{{ __('Coaches') }}</a>
                <a href="#galeri" class="nav-link">{{ __('Gallery') }}</a>
            </div>

            <div class="nav-auth flex items-center gap-4">
                <div class="flex items-center gap-2" style="border-right: 1px solid var(--border); padding-right: 1rem;">
                    <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'font-bold' : '' }}" style="text-decoration: none; color: inherit;">EN</a>
                    <span>|</span>
                    <a href="{{ route('lang.switch', 'id') }}" class="{{ app()->getLocale() == 'id' ? 'font-bold' : '' }}" style="text-decoration: none; color: inherit;">ID</a>
                </div>
                <div>
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">{{ __('Admin Dashboard') }}</a>
                        @else
                            <a href="{{ route('user.dashboard') }}" class="btn btn-outline">{{ __('My Dashboard') }}</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary">{{ __('Logout') }}</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline">{{ __('Log in') }}</a>
                        <a href="{{ route('register') }}" class="btn btn-primary">{{ __('Register') }}</a>
                    @endauth
                </div>
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
                <p class="text-muted">{{ __('The best padel court reservation platform with complete facilities and professional coaches.') }}</p>
            </div>
            <div>
                <h4 class="mb-4">{{ __('Links') }}</h4>
                <ul class="flex flex-col gap-2 text-muted">
                    <li><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                    <li><a href="#lapangan">{{ __('Courts') }}</a></li>
                    <li><a href="#fasilitas">{{ __('Facilities') }}</a></li>
                    <li><a href="#galeri">{{ __('Gallery') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 class="mb-4">{{ __('Contact') }}</h4>
                <ul class="flex flex-col gap-2 text-muted">
                    <li><i class="fa-solid fa-phone mr-2"></i> 0800 1111 2222</li>
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
