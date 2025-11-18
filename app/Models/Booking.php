<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tour_id',
        'name',
        'email',
        'phone',
        'total_people',
        'adults',
        'children',
        'infants',
        'special_requests',
        'status',
        'booking_code',
        'total_amount',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'booking_code';
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = self::generateBookingCode();
            }
        });
    }

    /**
     * Generate unique booking code.
     */
    public static function generateBookingCode()
    {
        do {
            $code = 'BK' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        } while (self::where('booking_code', $code)->exists());

        return $code;
    }

    /**
     * Get the user that owns the booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tour that is booked.
     */
    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Get the payments for the booking.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the review for the booking.
     */
    public function review()
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Get the successful payment.
     */
    public function getSuccessfulPayment()
    {
        return $this->payments()->where('status', 'success')->first();
    }

    /**
     * Get the latest payment.
     */
    public function getLatestPayment()
    {
        return $this->payments()->latest()->first();
    }

    /**
     * Check if booking is paid.
     */
    public function isPaid()
    {
        return $this->status === 'confirmed' && $this->getSuccessfulPayment() !== null;
    }

    /**
     * Check if booking can be paid.
     */
    public function canPay()
    {
        return $this->status === 'pending' && !$this->isPaid();
    }

    public function getCancellationError(): ?string
    {
        if ($this->status === 'cancelled') {
            return 'Đơn đặt chỗ này đã bị hủy trước đó.';
        }

        if ($this->status === 'completed') {
            return 'Tour đã hoàn thành, không thể hủy.';
        }

        if ($this->created_at->lt(now()->subHours(24))) {
            return 'Đã quá thời hạn 24 giờ kể từ khi đặt, bạn không thể hủy miễn phí.';
        }

        if ($this->tour->start_date) {
            $startDate = Carbon::parse($this->tour->start_date);
            
            if ($startDate->lte(now())) {
                return 'Tour đã khởi hành hoặc đã kết thúc, không thể hủy.';
            }
        }

        if (!in_array($this->status, ['pending', 'confirmed'])) {
            return 'Trạng thái đơn hàng không hợp lệ để thực hiện hủy.';
        }

        return null;
    }


    public function canCancel(): bool
    {
        return $this->getCancellationError() === null;
    }

    /**
     * Check if booking can request refund.
     */
    public function canRequestRefund()
    {
        if (!$this->isPaid()) {
            return false;
        }

        $payment = $this->getSuccessfulPayment();
        
        // Không thể hoàn tiền nếu đã được hoàn rồi
        if ($payment && $payment->status === 'refunded') {
            return false;
        }

        // Có thể hoàn tiền nếu chưa đến ngày tour
        if ($this->tour->start_date) {
            return $this->tour->start_date > now();
        }

        return true;
    }

    /**
     * Get payment status for display.
     */
    public function getPaymentStatusAttribute()
    {
        // Đã hủy
        if ($this->status === 'cancelled') {
            return 'cancelled';
        }

        // Đã thanh toán
        $successfulPayment = $this->getSuccessfulPayment();
        if ($this->status === 'confirmed' && $successfulPayment) {
            // Đã hoàn tiền
            if ($successfulPayment->status === 'refunded') {
                return 'refunded';
            }
            
            return 'paid';
        }

        // Chờ thanh toán hoặc đang xử lý
        $latestPayment = $this->getLatestPayment();
        
        if (!$latestPayment) {
            return 'awaiting_payment';
        }

        if ($latestPayment->status === 'pending') {
            return 'processing';
        }

        if ($latestPayment->status === 'failed') {
            return 'payment_failed';
        }

        return 'awaiting_payment';
    }

    /**
     * Get status label for display.
     */
    public function getStatusLabelAttribute()
    {
        return match($this->payment_status) {
            'awaiting_payment' => 'Chờ Thanh Toán',
            'processing' => 'Đang Xử Lý',
            'payment_failed' => 'Thanh Toán Thất Bại',
            'paid' => 'Đã Thanh Toán',
            'refunded' => 'Đã Hoàn Tiền',
            'cancelled' => 'Đã Hủy',
            default => 'Không xác định',
        };
    }

    /**
     * Get status color for display.
     */
    public function getStatusColorAttribute()
    {
        return match($this->payment_status) {
            'awaiting_payment' => 'yellow',
            'processing' => 'blue',
            'payment_failed' => 'red',
            'paid' => 'green',
            'refunded' => 'purple',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }

    /**
     * Cancel the booking.
     */
    // app/Models/Booking.php

public function cancel()
{
    if (!$this->canCancel()) {
        return false;
    }

    $this->update(['status' => 'cancelled']);

    \App\Models\ActivityLog::create([
        'user_id' => auth()->id(),
        'action' => 'cancelled_booking',
        'description' => "Đã hủy đặt chỗ #{$this->booking_code}",
        'properties' => ['booking_id' => $this->id]
    ]);

    return true;
}

    /**
     * Confirm the booking.
     */
    public function confirm()
    {
        $this->update(['status' => 'confirmed']);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'confirmed_booking',
            'description' => "Đã xác nhận đặt chỗ #{$this->booking_code}",
            'properties' => [
                'booking_id' => $this->id,
                'tour_name' => $this->tour->name,
            ],
        ]);

        return true;
    }

    /**
     * Calculate total amount.
     */
    public function calculateTotalAmount()
    {
        $total = 0;
        
        $total += $this->adults * $this->tour->price_adult;
        $total += $this->children * $this->tour->price_child;
        $total += $this->infants * $this->tour->price_infant;

        return $total;
    }

    /**
     * Scope a query to only include bookings with specific status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include pending bookings.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include confirmed bookings.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope a query to only include cancelled bookings.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope a query to filter by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to filter by tour.
     */
    public function scopeByTour($query, $tourId)
    {
        return $query->where('tour_id', $tourId);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}
