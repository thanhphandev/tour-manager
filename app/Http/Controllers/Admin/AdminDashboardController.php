<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Destination;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Statistics Cards
        $totalTours = Tour::count();
        $activeTours = Tour::where('status', 'active')->count();
        $totalDestinations = Destination::where('is_active', true)->count();
        
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();
        
        $totalUsers = User::where('is_admin', false)->count();
        $newUsersThisMonth = User::where('is_admin', false)
            ->whereMonth('created_at', now()->month)
            ->count();
        
        // Revenue Statistics (using Payment model for accuracy)
        $totalRevenue = \App\Models\Payment::where('status', 'success')->sum('amount');
        $revenueThisMonth = \App\Models\Payment::where('status', 'success')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');
        $revenueLastMonth = \App\Models\Payment::where('status', 'success')
            ->whereMonth('paid_at', now()->subMonth()->month)
            ->whereYear('paid_at', now()->subMonth()->year)
            ->sum('amount');
        
        // Calculate revenue growth
        $revenueGrowth = 0;
        if ($revenueLastMonth > 0) {
            $revenueGrowth = (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100;
        }
        
        // Recent Bookings
        $recentBookings = Booking::with(['tour', 'user'])
            ->latest()
            ->take(10)
            ->get();
        
        // Popular Tours (most booked)
        $popularTours = Tour::withCount('bookings')
            ->with('destination')
            ->orderBy('bookings_count', 'desc')
            ->take(5)
            ->get();
        
        // Monthly Booking Chart Data (last 12 months)
        $monthlyBookings = Booking::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Booking Status Distribution
        $bookingStatusDistribution = Booking::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();
        
        // Top Destinations
        $topDestinations = Destination::withCount('tours')
            ->orderByDesc('tours_count')
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalTours',
            'activeTours',
            'totalDestinations',
            'totalBookings',
            'pendingBookings',
            'confirmedBookings',
            'cancelledBookings',
            'totalUsers',
            'newUsersThisMonth',
            'totalRevenue',
            'revenueThisMonth',
            'revenueGrowth',
            'recentBookings',
            'popularTours',
            'monthlyBookings',
            'bookingStatusDistribution',
            'topDestinations'
        ));
    }
}
