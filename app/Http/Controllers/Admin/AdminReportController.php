<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Tour;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function revenue(Request $request)
    {
        $period = $request->get('period', 'month'); // day, week, month, year
        $startDate = $request->get('start_date') ? \Carbon\Carbon::parse($request->get('start_date')) : now()->startOfMonth();
        $endDate = $request->get('end_date') ? \Carbon\Carbon::parse($request->get('end_date')) : now()->endOfMonth();

        // Revenue by date (using paid_at for accurate revenue reporting)
        $revenueData = Payment::where('payments.status', 'success')
            ->whereBetween('payments.paid_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(payments.paid_at) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Revenue by tour (using paid_at for accurate revenue reporting)
        $revenueByTour = Payment::where('payments.status', 'success')
            ->whereBetween('payments.paid_at', [$startDate, $endDate])
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('tours', 'bookings.tour_id', '=', 'tours.id')
            ->select('tours.name', 'tours.id', DB::raw('SUM(payments.amount) as total'))
            ->groupBy('tours.id', 'tours.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Revenue by payment provider (using paid_at for accurate revenue reporting)
        $revenueByProvider = Payment::where('payments.status', 'success')
            ->whereBetween('payments.paid_at', [$startDate, $endDate])
            ->select('payment_method', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get();

        $stats = [
            'total_revenue' => Payment::where('status', 'success')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount'),
            'total_bookings' => Booking::whereBetween('created_at', [$startDate, $endDate])->count(),
            'average_booking_value' => Payment::where('status', 'success')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->avg('amount'),
        ];

        return view('admin.reports.revenue', compact(
            'revenueData',
            'revenueByTour',
            'revenueByProvider',
            'stats',
            'startDate',
            'endDate'
        ));
    }

    public function bookings(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $request->get('start_date') ? \Carbon\Carbon::parse($request->get('start_date')) : now()->startOfMonth();
        $endDate = $request->get('end_date') ? \Carbon\Carbon::parse($request->get('end_date')) : now()->endOfMonth();

        // Bookings by status
        $bookingsByStatus = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // Bookings by tour
        $bookingsByTour = Booking::whereBetween('bookings.created_at', [$startDate, $endDate])
            ->join('tours', 'bookings.tour_id', '=', 'tours.id')
            ->select('tours.name', DB::raw('COUNT(*) as count'))
            ->groupBy('tours.id', 'tours.name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Daily bookings
        $dailyBookings = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $stats = [
            'total_bookings' => Booking::whereBetween('created_at', [$startDate, $endDate])->count(),
            'confirmed_bookings' => Booking::where('status', 'confirmed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'cancelled_bookings' => Booking::where('status', 'cancelled')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'average_people_per_booking' => Booking::whereBetween('created_at', [$startDate, $endDate])
                ->avg('total_people'),
        ];

        return view('admin.reports.bookings', compact(
            'bookingsByStatus',
            'bookingsByTour',
            'dailyBookings',
            'stats',
            'startDate',
            'endDate'
        ));
    }

    public function tours(Request $request)
    {
        // Most popular tours
        $popularTours = Tour::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->limit(10)
            ->get();

        // Tours by revenue (only successful payments)
        $toursByRevenue = Tour::join('bookings', 'tours.id', '=', 'bookings.tour_id')
            ->join('payments', 'bookings.id', '=', 'payments.booking_id')
            ->where('payments.status', 'success')
            ->whereNotNull('payments.paid_at')
            ->select('tours.name', 'tours.id', DB::raw('SUM(payments.amount) as revenue'), DB::raw('COUNT(DISTINCT payments.id) as payment_count'))
            ->groupBy('tours.id', 'tours.name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        // Tours by rating
        $toursByRating = Tour::leftJoin('reviews', 'tours.id', '=', 'reviews.tour_id')
            ->where('reviews.is_approved', true)
            ->select('tours.name', DB::raw('AVG(reviews.rating) as avg_rating'), DB::raw('COUNT(reviews.id) as review_count'))
            ->groupBy('tours.id', 'tours.name')
            ->having('review_count', '>', 0)
            ->orderByDesc('avg_rating')
            ->limit(10)
            ->get();

        return view('admin.reports.tours', compact(
            'popularTours',
            'toursByRevenue',
            'toursByRating'
        ));
    }

    public function customers(Request $request)
    {
        // Stats
        $totalCustomers = User::where('is_admin', false)->count();
        $newCustomers = User::where('is_admin', false)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();
        $activeCustomers = User::where('is_admin', false)
            ->has('bookings')
            ->count();

        // Top customers by bookings
        $topCustomers = User::withCount('bookings')
            ->where('is_admin', false)
            ->orderByDesc('bookings_count')
            ->limit(10)
            ->get();

        // Top customers by spending (only successful payments)
        $topSpenders = User::join('bookings', 'users.id', '=', 'bookings.user_id')
            ->join('payments', 'bookings.id', '=', 'payments.booking_id')
            ->where('payments.status', 'success')
            ->whereNotNull('payments.paid_at')
            ->where('users.is_admin', false)
            ->select('users.id', 'users.name', 'users.email', DB::raw('SUM(payments.amount) as total_spent'), DB::raw('COUNT(DISTINCT payments.id) as payment_count'))
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get();

        // All customers with details
        $customers = User::where('is_admin', false)
            ->withCount(['bookings', 'reviews'])
            ->orderByDesc('bookings_count')
            ->paginate(20);

        // Add total_spent to each customer
        $customers->getCollection()->transform(function ($customer) {
            $customer->total_spent = Payment::whereHas('booking', function($q) use ($customer) {
                $q->where('user_id', $customer->id);
            })->where('status', 'success')->sum('amount');
            return $customer;
        });

        return view('admin.reports.customers', compact(
            'totalCustomers',
            'newCustomers',
            'activeCustomers',
            'topCustomers',
            'topSpenders',
            'customers'
        ));
    }

    public function exportRevenuePdf(Request $request)
    {
        $startDate = $request->get('start_date') ? \Carbon\Carbon::parse($request->get('start_date')) : now()->startOfMonth();
        $endDate = $request->get('end_date') ? \Carbon\Carbon::parse($request->get('end_date')) : now()->endOfMonth();

        // Get the same data as revenue report (using paid_at)
        $revenueData = Payment::where('payments.status', 'success')
            ->whereBetween('payments.paid_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(payments.paid_at) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $revenueByTour = Payment::where('payments.status', 'success')
            ->whereBetween('payments.paid_at', [$startDate, $endDate])
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('tours', 'bookings.tour_id', '=', 'tours.id')
            ->select('tours.name', 'tours.id', DB::raw('SUM(payments.amount) as total'))
            ->groupBy('tours.id', 'tours.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $stats = [
            'total_revenue' => Payment::where('status', 'success')
                ->whereBetween('paid_at', [$startDate, $endDate])
                ->sum('amount'),
            'total_payments' => Payment::where('status', 'success')
                ->whereBetween('paid_at', [$startDate, $endDate])
                ->count(),
            'total_bookings' => Booking::whereBetween('created_at', [$startDate, $endDate])->count(),
            'average_booking_value' => Payment::where('status', 'success')
                ->whereBetween('paid_at', [$startDate, $endDate])
                ->avg('amount'),
        ];

        $pdf = Pdf::loadView('admin.reports.pdf.revenue', compact(
            'revenueData',
            'revenueByTour',
            'stats',
            'startDate',
            'endDate'
        ));

        return $pdf->download('bao-cao-doanh-thu-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportBookingsPdf(Request $request)
    {
        $startDate = $request->get('start_date') ? \Carbon\Carbon::parse($request->get('start_date')) : now()->startOfMonth();
        $endDate = $request->get('end_date') ? \Carbon\Carbon::parse($request->get('end_date')) : now()->endOfMonth();

        $bookingsByStatus = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        $bookingsByTour = Booking::whereBetween('bookings.created_at', [$startDate, $endDate])
            ->join('tours', 'bookings.tour_id', '=', 'tours.id')
            ->select('tours.name', DB::raw('COUNT(*) as count'))
            ->groupBy('tours.id', 'tours.name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $stats = [
            'total_bookings' => Booking::whereBetween('created_at', [$startDate, $endDate])->count(),
            'confirmed_bookings' => Booking::where('status', 'confirmed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'cancelled_bookings' => Booking::where('status', 'cancelled')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
        ];

        $pdf = Pdf::loadView('admin.reports.pdf.bookings', compact(
            'bookingsByStatus',
            'bookingsByTour',
            'stats',
            'startDate',
            'endDate'
        ));

        return $pdf->download('bao-cao-booking-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportToursPdf()
    {
        $popularTours = Tour::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->limit(10)
            ->get();

        $toursByRevenue = Tour::join('bookings', 'tours.id', '=', 'bookings.tour_id')
            ->join('payments', 'bookings.id', '=', 'payments.booking_id')
            ->where('payments.status', 'success')
            ->whereNotNull('payments.paid_at')
            ->select('tours.name', 'tours.id', DB::raw('SUM(payments.amount) as revenue'), DB::raw('COUNT(DISTINCT payments.id) as payment_count'))
            ->groupBy('tours.id', 'tours.name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        $toursByRating = Tour::leftJoin('reviews', 'tours.id', '=', 'reviews.tour_id')
            ->where('reviews.is_approved', true)
            ->select('tours.name', DB::raw('AVG(reviews.rating) as avg_rating'), DB::raw('COUNT(reviews.id) as review_count'))
            ->groupBy('tours.id', 'tours.name')
            ->having('review_count', '>', 0)
            ->orderByDesc('avg_rating')
            ->limit(10)
            ->get();

        $pdf = Pdf::loadView('admin.reports.pdf.tours', compact(
            'popularTours',
            'toursByRevenue',
            'toursByRating'
        ));

        return $pdf->download('bao-cao-tours-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportCustomersPdf()
    {
        $totalCustomers = User::where('is_admin', false)->count();
        $newCustomers = User::where('is_admin', false)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();
        $activeCustomers = User::where('is_admin', false)
            ->has('bookings')
            ->count();

        $topCustomers = User::withCount('bookings')
            ->where('is_admin', false)
            ->orderByDesc('bookings_count')
            ->limit(10)
            ->get();

        $topSpenders = User::join('bookings', 'users.id', '=', 'bookings.user_id')
            ->join('payments', 'bookings.id', '=', 'payments.booking_id')
            ->where('payments.status', 'success')
            ->whereNotNull('payments.paid_at')
            ->where('users.is_admin', false)
            ->select('users.id', 'users.name', 'users.email', DB::raw('SUM(payments.amount) as total_spent'), DB::raw('COUNT(DISTINCT payments.id) as payment_count'))
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get();

        $pdf = Pdf::loadView('admin.reports.pdf.customers', compact(
            'totalCustomers',
            'newCustomers',
            'activeCustomers',
            'topCustomers',
            'topSpenders'
        ));

        return $pdf->download('bao-cao-khach-hang-' . now()->format('Y-m-d') . '.pdf');
    }
}
