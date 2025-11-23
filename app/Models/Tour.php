<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Services\MarkdownService;

class Tour extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination_id',
        'name',
        'slug',
        'short_description',
        'full_description',
        'itinerary',
        'price_adult',
        'price_child',
        'price_infant',
        'duration_days',
        'duration_nights',
        'max_people',
        'start_date',
        'end_date',
        'status',
        'thumbnail',
        'featured',
    ];

    protected $casts = [
        'price_adult' => 'decimal:2',
        'price_child' => 'decimal:2',
        'price_infant' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'featured' => 'boolean',
    ];

    /**
     * Get the full description as HTML.
     */
    public function getFullDescriptionHtmlAttribute()
    {
        $markdown = app(MarkdownService::class);
        return $markdown->toHtml($this->full_description);
    }

    /**
     * Get the itinerary as HTML.
     */
    public function getItineraryHtmlAttribute()
    {
        $markdown = app(MarkdownService::class);
        return $markdown->toHtml($this->itinerary);
    }

    /**
     * Get the average rating.
     */
    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tour) {
            if (empty($tour->slug)) {
                $tour->slug = Str::slug($tour->name);
            }
        });

        static::updating(function ($tour) {
            if ($tour->isDirty('name')) {
                $tour->slug = Str::slug($tour->name);
            }
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the destination that owns the tour.
     */
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    /**
     * Get the images for the tour.
     */
    public function images()
    {
        return $this->hasMany(TourImage::class);
    }

    /**
     * Get the primary image for the tour.
     */
    public function primaryImage()
    {
        return $this->hasOne(TourImage::class)->where('is_primary', true);
    }

    /**
     * Get the bookings for the tour.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the reviews for the tour.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get approved reviews only.
     */
    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    /**
     * Check if tour is active.
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if tour is available for booking.
     */
    public function isAvailable()
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->end_date && $this->end_date < now()) {
            return false;
        }

        return true;
    }

    /**
     * Get available slots.
     */
    public function getAvailableSlots()
    {
        if (!$this->max_people) {
            return null; // Unlimited
        }

        $booked = $this->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->sum('total_people');

        return max(0, $this->max_people - $booked);
    }

    /**
     * Check if tour has available slots.
     */
    public function hasAvailableSlots($requiredSlots = 1)
    {
        $available = $this->getAvailableSlots();
        
        if ($available === null) {
            return true; // Unlimited
        }

        return $available >= $requiredSlots;
    }

    /**
     * Get the average rating.
     */
    public function getAverageRating()
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    /**
     * Get the total reviews count.
     */
    public function getTotalReviews()
    {
        return $this->approvedReviews()->count();
    }

    /**
     * Get thumbnail URL.
     */
    public function getThumbnailUrl()
    {
        $imagePath = $this->thumbnail;

        if (!$imagePath && $this->primaryImage) {
            $imagePath = $this->primaryImage->image_path;
        }

        if (!$imagePath && $this->images->isNotEmpty()) {
            $imagePath = $this->images->first()->image_path;
        }

        if ($imagePath) {
            if (Str::startsWith($imagePath, ['http://', 'https://'])) {
                return $imagePath;
            }
            return asset('storage/' . $imagePath);
        }

        // Ảnh mặc định nếu không có ảnh nào (Placeholder đẹp)
        return 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
    }



    /**
     * Scope a query to only include active tours.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include featured tours.
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Scope a query to filter by destination.
     */
    public function scopeByDestination($query, $destinationId)
    {
        return $query->where('destination_id', $destinationId);
    }

    /**
     * Scope a query to filter by price range.
     */
    public function scopePriceRange($query, $min = null, $max = null)
    {
        if ($min !== null) {
            $query->where('price_adult', '>=', $min);
        }

        if ($max !== null) {
            $query->where('price_adult', '<=', $max);
        }

        return $query;
    }

    /**
     * Scope a query to filter by duration.
     */
    public function scopeDuration($query, $days)
    {
        return $query->where('duration_days', $days);
    }

    /**
     * Scope a query to search tours.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('short_description', 'like', "%{$search}%")
              ->orWhere('full_description', 'like', "%{$search}%")
              ->orWhereHas('destination', function ($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%");
              });
        });
    }
}
