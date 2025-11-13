<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\SavedPlan;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', auth()->id())
            ->with('tour')
            ->latest()
            ->paginate(10);

        // $savedPlans = SavedPlan::where('user_id', auth()->id())->paginate(5);

        return view('profile.index', compact('bookings'));
    }
}