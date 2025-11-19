<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'payment_code',
        'payment_method',
        'amount',
        'status',
        'transaction_data',
        'transaction_id',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'transaction_data' => 'array',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'payment_code';
    }

    /**
     * Get the booking that owns the payment.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Generate unique payment code
     */
    public static function generatePaymentCode()
    {
        do {
            $code = 'PAY' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 10));
        } while (self::where('payment_code', $code)->exists());

        return $code;
    }

    /**
     * Mark payment as success
     */
    public function markAsSuccess($transactionId = null, $transactionData = [])
    {
        $this->update([
            'status' => 'success',
            'transaction_id' => $transactionId,
            'transaction_data' => $transactionData,
            'paid_at' => now(),
        ]);

        // Update booking with payment_id and status
        $this->booking->update([
            'status' => 'confirmed',
        ]);
    }

    /**
     * Mark payment as failed
     */
    public function markAsFailed($notes = null)
    {
        $this->update([
            'status' => 'failed',
            'notes' => $notes,
        ]);
    }
    
    /**
     * Scope to get only successful payments
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success')->whereNotNull('paid_at');
    }
    
    /**
     * Scope to get payments within date range (using paid_at)
     */
    public function scopePaidBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('paid_at', [$startDate, $endDate]);
    }
    
    /**
     * Scope to get payments by method
     */
    public function scopeByMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }
    
    /**
     * Get total revenue for successful payments
     */
    public static function getTotalRevenue($startDate = null, $endDate = null)
    {
        $query = self::successful();
        
        if ($startDate && $endDate) {
            $query->paidBetween($startDate, $endDate);
        }
        
        return $query->sum('amount');
    }
    
    /**
     * Get average payment amount
     */
    public static function getAverageAmount($startDate = null, $endDate = null)
    {
        $query = self::successful();
        
        if ($startDate && $endDate) {
            $query->paidBetween($startDate, $endDate);
        }
        
        return $query->avg('amount');
    }
}