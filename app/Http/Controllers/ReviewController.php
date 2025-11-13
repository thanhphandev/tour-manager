<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Tour;
use App\Models\Booking;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function create(Tour $tour)
    {
        // Check if user has booked this tour
        $hasBooking = auth()->user()->bookings()
            ->where('tour_id', $tour->id)
            ->where('status', 'confirmed')
            ->exists();

        if (!$hasBooking) {
            return redirect()->route('tours.show', $tour)
                ->with('error', 'Bạn cần đặt tour này để có thể đánh giá.');
        }

        // Check if user already reviewed
        $hasReviewed = auth()->user()->reviews()
            ->where('tour_id', $tour->id)
            ->exists();

        if ($hasReviewed) {
            return redirect()->route('tours.show', $tour)
                ->with('error', 'Bạn đã đánh giá tour này rồi.');
        }

        return view('reviews.create', compact('tour'));
    }

    public function store(Request $request, Tour $tour)
    {
        // Check if user has booked this tour
        $booking = auth()->user()->bookings()
            ->where('tour_id', $tour->id)
            ->where('status', 'confirmed')
            ->first();

        if (!$booking) {
            return redirect()->route('tours.show', $tour)
                ->with('error', 'Bạn cần đặt tour này để có thể đánh giá.');
        }

        // Check if user already reviewed
        $hasReviewed = auth()->user()->reviews()
            ->where('tour_id', $tour->id)
            ->exists();

        if ($hasReviewed) {
            return redirect()->route('tours.show', $tour)
                ->with('error', 'Bạn đã đánh giá tour này rồi.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'comment' => 'required|string|min:10',
        ]);

        $review = Review::create([
            'tour_id' => $tour->id,
            'user_id' => auth()->id(),
            'booking_id' => $booking->id,
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'comment' => $validated['comment'],
            'is_verified' => true, // Verified because user has confirmed booking
            'is_approved' => false, // Needs admin approval
        ]);

        return redirect()->route('tours.show', $tour)
            ->with('success', 'Cảm ơn bạn đã đánh giá! Review của bạn sẽ được hiển thị sau khi admin duyệt.');
    }

    public function myReviews()
    {
        $reviews = auth()->user()->reviews()
            ->with(['tour', 'booking'])
            ->latest()
            ->paginate(10);

        return view('reviews.my-reviews', compact('reviews'));
    }
}
