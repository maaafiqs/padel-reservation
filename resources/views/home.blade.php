@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<style>
    .hero-slider-img {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background-size: cover;
        background-position: center;
        opacity: 0;
        animation: hero-fade 16s infinite;
        z-index: 1;
    }
    .hero-slider-img:nth-child(1) { animation-delay: 0s; background-image: url('{{ asset("images/gabriel-martin-3bKPFv4PiGU-unsplash.webp") }}'); }
    .hero-slider-img:nth-child(2) { animation-delay: 4s; background-image: url('{{ asset("images/sidespin-padel-nwpmvvMwK4Q-unsplash.webp") }}'); }
    .hero-slider-img:nth-child(3) { animation-delay: 8s; background-image: url('{{ asset("images/ernesto-samaniego-6xsvt8sI03Y-unsplash.webp") }}'); }
    .hero-slider-img:nth-child(4) { animation-delay: 12s; background-image: url('{{ asset("images/raket padel.webp") }}'); }
    
    @keyframes hero-fade {
        0%, 20% { opacity: 1; transform: scale(1); }
        25%, 95% { opacity: 0; transform: scale(1.05); }
        100% { opacity: 1; transform: scale(1); }
    }
    
    /* Hero Buttons */
    .hero-btn-primary {
        background-color: #3b82f6; 
        color: white; 
        padding: 1rem 2rem; 
        font-size: 1.1rem; 
        border-radius: 99px; 
        font-weight: 700; 
        box-shadow: 0 4px 15px rgba(59,130,246,0.4); 
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
    }
    .hero-btn-primary:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 8px 25px rgba(59,130,246,0.6);
        background-color: #2563eb;
    }
    
    .hero-btn-outline {
        background-color: transparent; 
        border: 2px solid white; 
        color: white; 
        padding: 1rem 2rem; 
        font-size: 1.1rem; 
        border-radius: 99px; 
        font-weight: 600; 
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
    }
    .hero-btn-outline:hover {
        transform: translateY(-3px) scale(1.05);
        background-color: white;
        color: #111827;
        box-shadow: 0 8px 25px rgba(255,255,255,0.4);
    }
    
    /* Masonry Gallery CSS */
    .masonry-gallery {
        column-count: 1;
        column-gap: 1.5rem;
    }
    
    @media (min-width: 768px) {
        .masonry-gallery {
            column-count: 3;
        }
    }
    
    @media (min-width: 1024px) {
        .masonry-gallery {
            column-count: 4;
        }
    }
    
    .masonry-item {
        break-inside: avoid;
        margin-bottom: 1.5rem;
        border-radius: 1rem;
        overflow: hidden;
        position: relative;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        cursor: pointer;
    }
    
    .masonry-item img {
        width: 100%;
        display: block;
        transition: transform 0.7s ease;
    }
    
    .masonry-item::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.4) 0%, transparent 50%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .masonry-item:hover img {
        transform: scale(1.08);
    }
    
    .masonry-item:hover::after {
        opacity: 1;
    }
</style>

<!-- Hero Section -->
<section style="position: relative; overflow: hidden; min-height: 100vh; display: flex; align-items: center; padding-top: 80px;">
    
    <!-- CSS-Only Background Crossfade -->
    <div style="position: absolute; inset: 0; z-index: 1; overflow: hidden;">
        <div class="hero-slider-img"></div>
        <div class="hero-slider-img"></div>
        <div class="hero-slider-img"></div>
        <div class="hero-slider-img"></div>
    </div>
    
    <!-- Gradient Overlay (Dark on the left for text, clear on the right for photo) -->
    <div style="position: absolute; inset: 0; z-index: 2; background: linear-gradient(to right, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.6) 40%, rgba(0,0,0,0) 100%);"></div>

    <!-- Foreground Content -->
    <div class="container grid grid-cols-1 lg:grid-cols-2 items-center" style="position: relative; z-index: 10; padding: 4rem 1rem; margin: 0 auto;">
        
        <!-- Left: Text Content -->
        <div style="padding-right: 2rem;" data-aos="fade-right">
            <span style="display: inline-block; padding: 0.5rem 1rem; border-radius: 99px; background-color: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.3); margin-bottom: 1.5rem; backdrop-filter: blur(4px);">✨ {{ __('Best Choice 2026') }}</span>
            
            <h1 style="color: white; font-size: 4rem; font-weight: 800; line-height: 1.1; margin-bottom: 1.5rem; text-shadow: 0 4px 10px rgba(0,0,0,0.5);">
                {{ __('Playing Padel Is Getting') }}<br><span style="color: #3b82f6; text-shadow: 0 4px 15px rgba(59,130,246,0.4);">{{ __('Easier & Practical') }}</span>
            </h1>
            
            <p style="color: rgba(255,255,255,0.9); font-size: 1.25rem; margin-bottom: 2.5rem; max-width: 500px; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
                {{ __('Book courts, rent rackets, and schedule training with professional coaches in just a few clicks.') }}
            </p>
            
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="#lapangan" class="hero-btn-primary">{{ __('Book Now') }}</a>
                <a href="#fasilitas" class="hero-btn-outline">{{ __('View Facilities') }}</a>
            </div>
        </div>

        <!-- Right: Giant Swinging Racket Icon -->
        <div style="display: flex; justify-content: flex-end; align-items: center;" data-aos="zoom-in" data-aos-delay="200">
            <!-- Racket icon moved to auth pages -->
        </div>
    </div>
</section>

<!-- Lapangan Section -->
<section id="lapangan" class="py-24" style="background-color: var(--surface);">
    <div class="container">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl mb-4">{{ __('Our Court Options') }}</h2>
            <p class="text-muted text-lg max-w-2xl mx-auto">{{ __('We provide international standard indoor and outdoor courts for the best playing experience.') }}</p>
        </div>
        
        <div class="grid grid-cols-3 gap-8">
            @forelse($courts as $index => $court)
            <div class="card" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div style="height: 200px; background-color: var(--primary-light); border-radius: var(--radius-md) var(--radius-md) 0 0; margin: -1.5rem -1.5rem 1.5rem -1.5rem; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    @if($court->image)
                        <img src="{{ Storage::url($court->image) }}" alt="{{ $court->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <i class="fa-solid {{ strtolower($court->type) == 'indoor' ? 'fa-layer-group' : 'fa-sun' }} text-primary text-4xl"></i>
                    @endif
                </div>
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-xl">{{ __($court->name) }}</h3>
                    <span class="badge badge-success">{{ __('Available') }}</span>
                </div>
                <p class="text-muted mb-4">{{ $court->description ? __($court->description) : __('Lapangan ' . $court->type . ' berstandar internasional.') }}</p>
                <div class="flex justify-between items-center font-semibold">
                    <span class="text-primary text-lg">Rp {{ number_format($court->price_per_hour, 0, ',', '.') }} / Jam</span>
                    <a href="{{ route('user.reservations.create') }}" class="btn btn-primary" style="padding: 0.5rem 1rem;">Booking</a>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-8">
                <p class="text-muted text-lg">{{ __('No courts currently available.') }}</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Fasilitas Section -->
<section id="fasilitas" class="py-24">
    <div class="container">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl mb-4">{{ __('Our Complete Facilities') }}</h2>
            <p class="text-muted text-lg max-w-2xl mx-auto">{{ __('Enjoy the comfort of playing with the best facilities we have prepared especially for you.') }}</p>
        </div>
        
        <div class="grid grid-cols-3 gap-8 text-center">
            <div class="card" data-aos="fade-up" data-aos-delay="0">
                <i class="fa-solid fa-shower text-primary text-4xl mb-4"></i>
                <h3 class="text-xl mb-2">{{ __('Lockers & Showers') }}</h3>
                <p class="text-muted">{{ __('Clean, comfortable, and safe changing facilities equipped with warm water showers.') }}</p>
            </div>
            <div class="card" data-aos="fade-up" data-aos-delay="100">
                <i class="fa-solid fa-mug-hot text-primary text-4xl mb-4"></i>
                <h3 class="text-xl mb-2">{{ __('Cafe & Lounge') }}</h3>
                <p class="text-muted">{{ __('Relax after playing while enjoying a wide selection of food and snacks.') }}</p>
            </div>
            <div class="card" data-aos="fade-up" data-aos-delay="200">
                <i class="fa-solid fa-shop text-primary text-4xl mb-4"></i>
                <h3 class="text-xl mb-2">{{ __('Pro Shop') }}</h3>
                <p class="text-muted">{{ __('Rent or buy complete padel equipment of the highest quality from various brands.') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Pelatih Section -->
<section id="coach" class="py-24" style="background-color: var(--surface);">
    <div class="container">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl mb-4">{{ __('Our Professional Coaches') }}</h2>
            <p class="text-muted text-lg max-w-2xl mx-auto">{{ __('Improve your playing skills with direct guidance from experienced coaches.') }}</p>
        </div>
        
        <div class="grid grid-cols-4 gap-6">
            @forelse($coaches as $index => $coach)
            <div class="card text-center" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="mb-4 w-24 h-24 mx-auto rounded-full bg-primary-light flex items-center justify-center overflow-hidden">
                    @if($coach->image)
                        <img src="{{ Storage::url($coach->image) }}" alt="{{ $coach->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <i class="fa-solid fa-user-tie text-primary text-3xl"></i>
                    @endif
                </div>
                <h3 class="text-xl mb-1">{{ __($coach->name) }}</h3>
                <p class="text-sm text-primary mb-3 font-semibold">Rp {{ number_format($coach->price_per_hour, 0, ',', '.') }} {{ __('/ Hour') }}</p>
                <p class="text-muted text-sm">{{ __($coach->bio) }}</p>
            </div>
            @empty
            <div class="col-span-4 text-center py-8">
                <p class="text-muted text-lg">{{ __('No coaches currently available.') }}</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Galeri Section -->
<section id="galeri" class="py-24 bg-white">
    <div class="container">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl mb-4 font-bold">{{ __('Padel Gallery') }}</h2>
            <p class="text-muted text-lg max-w-2xl mx-auto">{{ __('Exciting moments and the best facilities we provide for you. Explore the fun of playing padel at our place.') }}</p>
        </div>
        
        <div class="masonry-gallery">
            <div class="masonry-item" data-aos="fade-up" data-aos-delay="0">
                <img src="{{ asset('images/gabriel-martin-jtQlmGObPPM-unsplash.webp') }}" alt="Gallery 1" loading="lazy">
            </div>
            <div class="masonry-item" data-aos="fade-up" data-aos-delay="100">
                <img src="{{ asset('images/fiqih-alfarish-Gqr4pHQXRQU-unsplash.webp') }}" alt="Gallery 2" loading="lazy">
            </div>
            <div class="masonry-item" data-aos="fade-up" data-aos-delay="200">
                <img src="{{ asset('images/cal-gao-VhICDvY7c54-unsplash.webp') }}" alt="Gallery 3" loading="lazy">
            </div>
            <div class="masonry-item" data-aos="fade-up" data-aos-delay="300">
                <img src="{{ asset('images/gabriel-martin-NvIA5PEnOZ0-unsplash.webp') }}" alt="Gallery 4" loading="lazy">
            </div>
            <div class="masonry-item" data-aos="fade-up" data-aos-delay="400">
                <img src="{{ asset('images/retrato-deportivo-52vXXMaeUl0-unsplash.webp') }}" alt="Gallery 5" loading="lazy">
            </div>
            <div class="masonry-item" data-aos="fade-up" data-aos-delay="500">
                <img src="{{ asset('images/gabriel-martin-a49jWAKBoZM-unsplash.webp') }}" alt="Gallery 6" loading="lazy">
            </div>
        </div>
    </div>
</section>

<!-- Info & Announcements -->
<section class="py-24">
    <div class="container grid grid-cols-2 gap-16 items-center">
        <div data-aos="fade-right">
            <span class="text-primary font-bold tracking-wider uppercase text-sm mb-2 block">{{ __('Latest Information') }}</span>
            <h2 class="text-4xl mb-6">{{ __('Get a Special Discount for New Users!') }}</h2>
            <p class="text-lg text-muted mb-6">{{ __('Register now and enjoy up to a 20% discount on your first reservation. We also regularly hold amateur tournaments every month.') }}</p>
            <ul class="flex flex-col gap-4 mb-8">
                <li class="flex items-center gap-3 text-lg"><i class="fa-solid fa-check-circle text-primary"></i> {{ __('Free Locker & Shower Facilities') }}</li>
                <li class="flex items-center gap-3 text-lg"><i class="fa-solid fa-check-circle text-primary"></i> {{ __('Cheap Racket & Ball Rental') }}</li>
                <li class="flex items-center gap-3 text-lg"><i class="fa-solid fa-check-circle text-primary"></i> {{ __('Comfortable Cafe & Waiting Room') }}</li>
            </ul>
            <a href="{{ route('register') }}" class="btn btn-primary">{{ __('Register Now') }}</a>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div class="card bg-primary text-white" style="background-color: var(--primary); color: white;" data-aos="fade-up" data-aos-delay="100">
                <i class="fa-solid fa-trophy text-4xl mb-4"></i>
                <h3 class="text-xl text-white mb-2">{{ __('Padel Tournaments') }}</h3>
                <p style="color: rgba(255,255,255,0.8)">{{ __('Join our monthly tournaments and win exciting prizes.') }}</p>
            </div>
            <div class="card" style="margin-top: 2rem;" data-aos="fade-up" data-aos-delay="200">
                <i class="fa-solid fa-user-tie text-primary text-4xl mb-4"></i>
                <h3 class="text-xl mb-2">{{ __('Expert Coaches') }}</h3>
                <p class="text-muted">{{ __('Professional coaches available for beginner to advanced classes.') }}</p>
            </div>
        </div>
    </div>
</section>

@endsection
