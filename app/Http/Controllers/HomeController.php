<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Tour;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredTours = Tour::where('featured', true)
            ->where('status', 'active')
            ->where('max_people', '>', 0)
            ->with('destination', 'primaryImage')
            ->limit(6)
            ->get();

        $latestTours = Tour::where('status', 'active')
            ->where('max_people', '>', 0)
            ->with('destination', 'primaryImage')
            ->latest()
            ->limit(6)
            ->get();

        return view('home', compact('featuredTours', 'latestTours'));
    }

    public function tours(Request $request)
    {
        $query = Tour::query()
            ->where('status', 'active')
            ->where('max_people', '>', 0)
            ->with(['destination', 'primaryImage']);

        // Search text (tour name or destination name)
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhereHas('destination', function ($d) use ($search) {
                    $d->where('name', 'LIKE', "%{$search}%");
                });
            });
        }

        // Destination filter (slug)
        if ($request->filled('destination')) {
            $query->whereHas('destination', function ($d) use ($request) {
                $d->where('slug', $request->destination);
            });
        }

        // Price filter (keyword)
        if ($request->filled('price')) {
            switch ($request->price) {
                case 'under-5m':
                    $query->where('price_adult', '<=', 5000000);
                    break;
                case '5m-10m':
                    $query->where('price_adult', '<=', 10000000)
                          ->where('price_adult', '>', 5000000);
                    break;
                case '10m-20m':
                    $query->where('price_adult', '<=', 20000000)
                          ->where('price_adult', '>', 10000000);
                    break;
                case 'over-20m':
                    $query->where('price_adult', '>', 20000000);
                    break;
            }
        }

        // Duration filter (range)
        if ($request->filled('duration')) {
            $range = explode('-', $request->duration);

            if (count($range) === 2) {
                $min = (int) $range[0];
                $max = (int) $range[1];

                $query->whereBetween('duration_days', [$min, $max]);
            }
        }

        // Default order
        $query->latest();

        // Paginate and preserve query string
        $tours = $query->paginate(12)->withQueryString();

        $destinations = Destination::where('is_active', true)->get();

        return view('tours.index', compact('tours', 'destinations'));
    }


    public function tourDetails(Tour $tour)
    {
        $tour->loadCount('approvedReviews as reviews_count')
             ->load([
                'destination', 
                'images',
                'approvedReviews' => function($query) {
                    $query->with('user')->latest();
                }
             ]);

        
        return view('tours.show', compact('tour'));
    }

    public function destinations()
    {
        $destinations = Destination::where('is_active', true)->get();
        return view('destinations.index', compact('destinations'));
    }

    public function destinationDetails(Destination $destination)
    {
        $tours = $destination->tours()
            ->where('status', 'active')
            ->where('max_people', '>', 0)
            ->with('primaryImage')
            ->paginate(12);

        return view('destinations.show', compact('destination', 'tours'));
    }
}