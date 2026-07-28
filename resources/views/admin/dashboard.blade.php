@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
    .col-span-2 {
        grid-column: span 2;
    }
    .col-span-1 {
        grid-column: span 1;
    }
    .stat-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    @media (max-width: 992px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
        .col-span-2, .col-span-1 {
            grid-column: span 1;
        }
    }
    
    .shortcuts-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 1rem;
    }
    .shortcut-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        gap: 0.75rem;
        padding: 1.25rem !important;
        transition: var(--transition);
        text-decoration: none;
    }
    .shortcut-card:hover {
        border-color: var(--primary) !important;
        background-color: var(--primary-light) !important;
        transform: translateY(-3px);
        box-shadow: var(--shadow-md) !important;
    }
    @media (max-width: 992px) {
        .shortcuts-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    @media (max-width: 576px) {
        .shortcuts-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl">{{ __('Dashboard Overview') }}</h1>
</div>

        <div class="grid grid-cols-4 gap-6 mb-8">
            <div class="card stat-card">
                <div>
                    <h3 class="text-muted text-sm uppercase mb-2" style="font-weight: 500; font-size: 0.75rem; letter-spacing: 0.05em;">{{ __('Total Reservations') }}</h3>
                    <p class="text-3xl font-bold text-primary" style="color: var(--primary);">{{ number_format($totalReservations, 0, ',', '.') }}</p>
                </div>
                <div class="stat-icon" style="background-color: rgba(37, 99, 235, 0.1); color: var(--primary);">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
            </div>
            <div class="card stat-card">
                <div>
                    <h3 class="text-muted text-sm uppercase mb-2" style="font-weight: 500; font-size: 0.75rem; letter-spacing: 0.05em;">{{ __('Total Revenue') }}</h3>
                    <p class="text-3xl font-bold" style="color: #10b981;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="stat-icon" style="background-color: rgba(16, 185, 129, 0.1); color: #10b981;">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
            <div class="card stat-card">
                <div>
                    <h3 class="text-muted text-sm uppercase mb-2" style="font-weight: 500; font-size: 0.75rem; letter-spacing: 0.05em;">{{ __('Total Users') }}</h3>
                    <p class="text-3xl font-bold" style="color: #f59e0b;">{{ number_format($totalUsers, 0, ',', '.') }}</p>
                </div>
                <div class="stat-icon" style="background-color: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="card stat-card">
                <div>
                    <h3 class="text-muted text-sm uppercase mb-2" style="font-weight: 500; font-size: 0.75rem; letter-spacing: 0.05em;">{{ __('Available Courts') }}</h3>
                    <p class="text-3xl font-bold text-success" style="color: #8b5cf6;">{{ $activeCourts }}</p>
                </div>
                <div class="stat-icon" style="background-color: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                    <i class="fa-solid fa-table-tennis-paddle-ball"></i>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions Panel -->
        <h3 class="text-xl mb-4 font-semibold" style="margin-top: 1rem;">{{ __('Quick Shortcuts') }}</h3>
        <div class="shortcuts-grid mb-8">
            <a href="{{ route('admin.courts.create') }}" class="card shortcut-card">
                <div class="stat-icon" style="background-color: rgba(37, 99, 235, 0.1); color: var(--primary);">
                    <i class="fa-solid fa-square-plus"></i>
                </div>
                <span class="text-sm font-medium" style="color: var(--text-main); margin-top: 0.25rem;">{{ __('Add Court') }}</span>
            </a>
            <a href="{{ route('admin.coaches.create') }}" class="card shortcut-card">
                <div class="stat-icon" style="background-color: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <span class="text-sm font-medium" style="color: var(--text-main); margin-top: 0.25rem;">{{ __('Add Coach') }}</span>
            </a>
            <a href="{{ route('admin.reservations.index') }}" class="card shortcut-card">
                <div class="stat-icon" style="background-color: rgba(16, 185, 129, 0.1); color: #10b981;">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <span class="text-sm font-medium" style="color: var(--text-main); margin-top: 0.25rem;">{{ __('Manage Reservations') }}</span>
            </a>
            <a href="{{ route('admin.announcements.create') }}" class="card shortcut-card">
                <div class="stat-icon" style="background-color: rgba(236, 72, 153, 0.1); color: #ec4899;">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                <span class="text-sm font-medium" style="color: var(--text-main); margin-top: 0.25rem;">{{ __('Create Announcement') }}</span>
            </a>
            <a href="{{ route('admin.discounts.index') }}" class="card shortcut-card">
                <div class="stat-icon" style="background-color: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                    <i class="fa-solid fa-tags"></i>
                </div>
                <span class="text-sm font-medium" style="color: var(--text-main); margin-top: 0.25rem;">{{ __('Manage Discounts') }}</span>
            </a>
            <a href="{{ route('admin.backup.index') }}" class="card shortcut-card">
                <div class="stat-icon" style="background-color: rgba(100, 116, 139, 0.1); color: #64748b;">
                    <i class="fa-solid fa-database"></i>
                </div>
                <span class="text-sm font-medium" style="color: var(--text-main); margin-top: 0.25rem;">{{ __('Backup & Restore') }}</span>
            </a>
        </div>

        <div class="dashboard-grid mb-8">
            <!-- Chart 1: Daily Reservations (Bar) - Column span 2 -->
            <div class="card col-span-2">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl" style="font-weight: 600;">{{ __('Reservation Trend (Last 7 Days)') }}</h3>
                    <span class="badge" style="background-color: rgba(37, 99, 235, 0.1); color: var(--primary); padding: 0.25rem 0.75rem; border-radius: var(--radius-md); font-size: 0.75rem; font-weight: 600;">{{ __('Daily') }}</span>
                </div>
                <div style="position: relative; height: 320px; width: 100%;">
                    <canvas id="reservationsChart"></canvas>
                </div>
            </div>

            <!-- Chart 2: Court Share (Doughnut) - Column span 1 -->
            <div class="card col-span-1">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl" style="font-weight: 600;">{{ __('Court Popularity') }}</h3>
                </div>
                <div style="position: relative; height: 280px; width: 100%; display: flex; align-items: center; justify-content: center;">
                    <canvas id="courtShareChart"></canvas>
                </div>
            </div>
        </div>

        <div class="dashboard-grid mb-8">
            <!-- Chart 3: Monthly Revenue (Area Line Chart) - Column span 2 -->
            <div class="card col-span-2">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl" style="font-weight: 600;">{{ __('Monthly Revenue Trend (Last 6 Months)') }}</h3>
                    <span class="badge" style="background-color: rgba(16, 185, 129, 0.1); color: #10b981; padding: 0.25rem 0.75rem; border-radius: var(--radius-md); font-size: 0.75rem; font-weight: 600;">{{ __('Financial') }}</span>
                </div>
                <div style="position: relative; height: 320px; width: 100%;">
                    <canvas id="monthlyRevenueChart"></canvas>
                </div>
            </div>

            <!-- Chart 4: Popular Booking Hours (Horizontal Bar Chart) - Column span 1 -->
            <div class="card col-span-1">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl" style="font-weight: 600;">{{ __('Popular Booking Hours') }}</h3>
                </div>
                <div style="position: relative; height: 280px; width: 100%;">
                    <canvas id="popularHoursChart"></canvas>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Global options for Chart.js
                Chart.defaults.font.family = "'Outfit', sans-serif";
                Chart.defaults.color = '#64748b'; // var(--text-muted)

                // 1. Daily Reservations Chart (Bar Chart with gradient)
                var ctx1 = document.getElementById('reservationsChart').getContext('2d');
                var grad1 = ctx1.createLinearGradient(0, 0, 0, 300);
                grad1.addColorStop(0, 'rgba(37, 99, 235, 0.85)');
                grad1.addColorStop(1, 'rgba(37, 99, 235, 0.15)');

                new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [{
                            label: '{{ __('Reservations') }}',
                            data: {!! json_encode($chartData) !!},
                            backgroundColor: grad1,
                            borderColor: '#2563eb',
                            borderWidth: 1.5,
                            borderRadius: 6,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                grid: { display: false }
                            },
                            y: {
                                beginAtZero: true,
                                grid: { borderDash: [4, 4], color: '#e2e8f0' },
                                ticks: { stepSize: 1 }
                            }
                        }
                    }
                });

                // 2. Court Share Chart (Doughnut Chart)
                var ctx2 = document.getElementById('courtShareChart').getContext('2d');
                new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($courtLabels) !!},
                        datasets: [{
                            data: {!! json_encode($courtData) !!},
                            backgroundColor: [
                                '#2563eb', // Blue
                                '#8b5cf6', // Violet
                                '#10b981', // Emerald
                                '#f59e0b', // Amber
                                '#ec4899'  // Pink
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    padding: 15,
                                    font: { size: 11 }
                                }
                            }
                        },
                        cutout: '70%'
                    }
                });

                // 3. Monthly Revenue Chart (Line Chart with area gradient)
                var ctx3 = document.getElementById('monthlyRevenueChart').getContext('2d');
                var grad3 = ctx3.createLinearGradient(0, 0, 0, 300);
                grad3.addColorStop(0, 'rgba(16, 185, 129, 0.35)');
                grad3.addColorStop(1, 'rgba(16, 185, 129, 0.01)');

                new Chart(ctx3, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($monthlyRevenueLabels) !!},
                        datasets: [{
                            label: '{{ __('Revenue') }}',
                            data: {!! json_encode($monthlyRevenueData) !!},
                            backgroundColor: grad3,
                            borderColor: '#10b981',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false }
                            },
                            y: {
                                beginAtZero: true,
                                grid: { borderDash: [4, 4], color: '#e2e8f0' },
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + new Intl.NumberFormat('id-ID', {
                                            notation: 'compact',
                                            compactDisplay: 'short'
                                        }).format(value);
                                    }
                                }
                            }
                        }
                    }
                });

                // 4. Popular Booking Hours (Horizontal Bar Chart)
                var ctx4 = document.getElementById('popularHoursChart').getContext('2d');
                var grad4 = ctx4.createLinearGradient(0, 0, 300, 0);
                grad4.addColorStop(0, 'rgba(139, 92, 246, 0.15)');
                grad4.addColorStop(1, 'rgba(139, 92, 246, 0.85)');

                new Chart(ctx4, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($popularHoursLabels) !!},
                        datasets: [{
                            label: '{{ __('Total Bookings') }}',
                            data: {!! json_encode($popularHoursData) !!},
                            backgroundColor: grad4,
                            borderColor: '#8b5cf6',
                            borderWidth: 1.5,
                            borderRadius: 4,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                grid: { borderDash: [4, 4], color: '#e2e8f0' },
                                ticks: { stepSize: 1 }
                            },
                            y: {
                                grid: { display: false }
                            }
                        }
                    }
                });
            });
        </script>
@endsection
