<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Tour;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['tour', 'user', 'booking']);

        // Filter by approval status
        if ($request->has('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        // Filter by rating
        if ($request->has('rating') && $request->rating) {
            $query->where('rating', $request->rating);
        }

        // Filter by tour
        if ($request->has('tour_id') && $request->tour_id) {
            $query->where('tour_id', $request->tour_id);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('comment', 'like', "%{$search}%");
            });
        }

        $reviews = $query->latest()->paginate(20);
        $tours = Tour::orderBy('name')->get();

        // Statistics
        $stats = [
            'total' => Review::count(),
            'approved' => Review::where('is_approved', true)->count(),
            'pending' => Review::where('is_approved', false)->count(),
            'average_rating' => round(Review::where('is_approved', true)->avg('rating'), 1),
        ];

        return view('admin.reviews.index', compact('reviews', 'tours', 'stats'));
    }

    public function show(Review $review)
    {
        $review->load(['tour', 'user', 'booking']);
        return view('admin.reviews.show', compact('review'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        
        ActivityLog::log(
            "Approved review #{$review->id} for tour: {$review->tour->name}",
            $review,
            auth()->user()
        );

        return back()->with('success', 'Review đã được phê duyệt.');
    }

    public function reject(Review $review)
    {
        $review->update(['is_approved' => false]);
        
        ActivityLog::log(
            "Rejected review #{$review->id} for tour: {$review->tour->name}",
            $review,
            auth()->user()
        );

        return back()->with('success', 'Review đã bị từ chối.');
    }

    public function destroy(Review $review)
    {
        $tourName = $review->tour->name;
        $review->delete();
        
        ActivityLog::log(
            "Deleted review for tour: {$tourName}",
            null,
            auth()->user()
        );

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Đã xóa review thành công.');
    }
}
