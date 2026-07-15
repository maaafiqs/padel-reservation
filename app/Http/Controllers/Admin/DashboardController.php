<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Stats Bar
        $totalUsers = \App\Models\User::where('role', 'user')->count();
        $totalReservations = \App\Models\Reservation::count();
        $totalRevenue = \App\Models\Reservation::whereIn('status', ['confirmed', 'completed'])->sum('final_price');
        $activeCourts = \App\Models\Court::where('status', 'available')->count();

        // 2. Bar Chart Data (Last 7 Days Reservations)
        $chartData = [];
        $chartLabels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subDays($i);
            $chartLabels[] = $date->translatedFormat('d M');
            $count = \App\Models\Reservation::whereDate('reservation_date', $date->format('Y-m-d'))->count();
            $chartData[] = $count;
        }

        // 3. Court Reservations Distribution (Doughnut Chart)
        $courts = \App\Models\Court::all();
        $courtLabels = [];
        $courtData = [];
        foreach ($courts as $court) {
            $courtLabels[] = $court->name;
            $courtData[] = \App\Models\Reservation::where('court_id', $court->id)->count();
        }

        // 4. Monthly Revenue Trend (Line Area Chart)
        $monthlyRevenueLabels = [];
        $monthlyRevenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subMonths($i);
            $monthlyRevenueLabels[] = $date->translatedFormat('F Y');
            $revenue = \App\Models\Reservation::whereIn('status', ['confirmed', 'completed'])
                ->whereMonth('reservation_date', $date->month)
                ->whereYear('reservation_date', $date->year)
                ->sum('final_price');
            $monthlyRevenueData[] = (int)$revenue;
        }

        // 5. Popular Booking Hours (Horizontal Bar Chart)
        $popularHours = \App\Models\Reservation::select('start_time', \DB::raw('count(*) as count'))
            ->groupBy('start_time')
            ->orderBy('start_time', 'asc')
            ->get();
        $popularHoursLabels = $popularHours->pluck('start_time')->toArray();
        $popularHoursData = $popularHours->pluck('count')->toArray();

        if (empty($popularHoursLabels)) {
            $popularHoursLabels = ['08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00'];
            $popularHoursData = [0, 0, 0, 0, 0, 0, 0];
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalReservations',
            'totalRevenue',
            'activeCourts',
            'chartLabels',
            'chartData',
            'courtLabels',
            'courtData',
            'monthlyRevenueLabels',
            'monthlyRevenueData',
            'popularHoursLabels',
            'popularHoursData'
        ));
    }
}
