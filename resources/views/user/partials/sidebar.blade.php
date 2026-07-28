    <aside class="sidebar">
        <h3 class="text-xl mb-6 text-primary">{{ __('User Menu') }}</h3>
        <nav class="sidebar-menu">
            <a href="{{ route('user.dashboard') }}" class="sidebar-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-gauge"></i> {{ __('Dashboard') }}</a>
            <a href="{{ route('user.reservations.create') }}" class="sidebar-link {{ request()->routeIs('user.reservations.create') ? 'active' : '' }}"><i class="fa-solid fa-calendar-plus"></i> {{ __('Make a Reservation') }}</a>
            <a href="{{ route('user.reservations.index') }}" class="sidebar-link {{ request()->routeIs('user.reservations.index') ? 'active' : '' }}"><i class="fa-solid fa-clock-rotate-left"></i> {{ __('Booking History') }}</a>
            <a href="{{ route('user.profile.edit') }}" class="sidebar-link {{ request()->routeIs('user.profile.*') ? 'active' : '' }}"><i class="fa-solid fa-user"></i> {{ __('My Profile') }}</a>
            <form method="POST" action="{{ route('logout') }}" style="margin-top: 1rem; border-top: 1px solid var(--border); padding-top: 1rem;">
                @csrf
                <button type="submit" class="sidebar-link text-danger w-full" style="text-align: left; background: none; border: none; cursor: pointer; display: flex; align-items: center; width: 100%; color: #ef4444; gap: 0.75rem;">
                    <i class="fa-solid fa-right-from-bracket"></i> {{ __('Logout') }}
                </button>
            </form>
        </nav>
    </aside>
