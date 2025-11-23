<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminTourController;
use App\Http\Controllers\Admin\AdminTourImageController;
use App\Http\Controllers\Admin\AdminDestinationController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminActivityLogController;
use App\Http\Controllers\Admin\AdminEmailTemplateController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminEmailController;

/*
|--------------------------------------------------------------------------
| 🌐 PUBLIC ROUTES - Client Side
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// ----- Tours -----
Route::prefix('tours')->group(function () {
    Route::get('/', [HomeController::class, 'tours'])->name('tours.index');
    Route::get('/{tour:slug}', [HomeController::class, 'tourDetails'])->name('tours.show');
    Route::get('/{tour:slug}/book', [BookingController::class, 'create'])->name('bookings.create')->middleware('auth');
    Route::post('/{tour:slug}/book', [BookingController::class, 'store'])->name('bookings.store')->middleware('auth');
});

// ----- Destinations -----
Route::prefix('destinations')->group(function () {
    Route::get('/', [HomeController::class, 'destinations'])->name('destinations.index');
    Route::get('/{destination:slug}', [HomeController::class, 'destinationDetails'])->name('destinations.show');
});

// ----- Bookings -----
Route::prefix('bookings')->middleware('auth')->group(function () {
    Route::get('/history', [BookingController::class, 'history'])->name('bookings.history');
    Route::get('/{booking:booking_code}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/{booking:booking_code}/success', [BookingController::class, 'success'])->name('bookings.success');
    Route::post('/{booking:booking_code}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
});

// ----- Payments -----
Route::prefix('payments')->middleware('auth')->group(function () {
    Route::get('/{booking:booking_code}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/{booking:booking_code}/mock', [PaymentController::class, 'processMock'])->name('payments.process.mock');
    Route::post('/{booking:booking_code}/vnpay', [PaymentController::class, 'processVNPay'])->name('payments.process.vnpay');
    Route::post('/{booking:booking_code}/paypal', [PaymentController::class, 'processPayPal'])->name('payments.process.paypal');
    
    // Callbacks
    Route::get('/vnpay/callback', [PaymentController::class, 'vnpayCallback'])->name('payments.vnpay.callback');
    Route::get('/paypal/callback', [PaymentController::class, 'paypalCallback'])->name('payments.paypal.callback');
    Route::get('/paypal/cancel', [PaymentController::class, 'paypalCancel'])->name('payments.paypal.cancel');
    
    // Error page
    Route::get('/{booking:booking_code}/error', [PaymentController::class, 'error'])->name('payments.error');
});

// ----- Reviews -----
Route::prefix('reviews')->middleware('auth')->group(function () {
    Route::get('/my-reviews', [ReviewController::class, 'myReviews'])->name('reviews.my-reviews');
    Route::get('/{tour}/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/{tour}', [ReviewController::class, 'store'])->name('reviews.store');
});



/*
|--------------------------------------------------------------------------
| 🛠️ ADMIN ROUTES - Management Side
|--------------------------------------------------------------------------
| Dành cho admin quản lý toàn bộ hệ thống.
| Middleware: auth + admin
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        // ----- Dashboard -----
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // ----- Tours -----
        Route::resource('tours', AdminTourController::class);
        Route::resource('tours.images', AdminTourImageController::class)->shallow();
        Route::put('/tours/{tour}/images/{image}/primary', [AdminTourImageController::class, 'setPrimary'])
            ->name('tours.images.primary');

        // ----- Destinations -----
        Route::resource('destinations', AdminDestinationController::class);

        // ----- Bookings -----
        Route::resource('bookings', AdminBookingController::class);
        Route::put('/bookings/{booking}/confirm', [AdminBookingController::class, 'confirm'])->name('bookings.confirm');
        Route::put('/bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel');
        Route::get('/bookings/{booking}/invoice-pdf', [AdminBookingController::class, 'exportInvoicePdf'])->name('bookings.invoice.pdf');
        Route::get('/bookings/{booking}/invoice-view', [AdminBookingController::class, 'viewInvoicePdf'])->name('bookings.invoice.view');


        // ----- Users -----
        Route::resource('users', AdminUserController::class);
        Route::put('/users/{user}/toggle', [AdminUserController::class, 'toggleStatus'])->name('users.toggle');

        // ----- Reviews -----
        Route::resource('reviews', AdminReviewController::class)->only(['index', 'show', 'destroy']);
        Route::put('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
        Route::put('/reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');

        // ----- Payments -----
        Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
        Route::post('/payments/{payment}/refund', [AdminPaymentController::class, 'refund'])->name('payments.refund');

        // ----- Activity Logs -----
        Route::get('/activity-logs', [AdminActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('/activity-logs/{activityLog}', [AdminActivityLogController::class, 'show'])->name('activity-logs.show');
        Route::delete('/activity-logs/{activityLog}', [AdminActivityLogController::class, 'destroy'])->name('activity-logs.destroy');
        Route::post('/activity-logs/clear', [AdminActivityLogController::class, 'clear'])->name('activity-logs.clear');

    // ----- Reports -----
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [AdminReportController::class, 'index'])->name('index');
        Route::get('/revenue', [AdminReportController::class, 'revenue'])->name('revenue');
        Route::get('/bookings', [AdminReportController::class, 'bookings'])->name('bookings');
        Route::get('/tours', [AdminReportController::class, 'tours'])->name('tours');
        Route::get('/customers', [AdminReportController::class, 'customers'])->name('customers');
        
        // PDF Exports
        Route::get('/revenue/pdf', [AdminReportController::class, 'exportRevenuePdf'])->name('revenue.pdf');
        Route::get('/bookings/pdf', [AdminReportController::class, 'exportBookingsPdf'])->name('bookings.pdf');
        Route::get('/tours/pdf', [AdminReportController::class, 'exportToursPdf'])->name('tours.pdf');
        Route::get('/customers/pdf', [AdminReportController::class, 'exportCustomersPdf'])->name('customers.pdf');
    });

});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
