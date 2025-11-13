<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\TourImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminTourImageController extends Controller
{
    public function index(Tour $tour)
    {
        $images = $tour->images()->orderBy('order')->get();
        return view('admin.tour-images.index', compact('tour', 'images'));
    }

    public function store(Request $request, Tour $tour)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|max:2048',
        ]);

        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('tours', 'public');
            
            TourImage::create([
                'tour_id' => $tour->id,
                'image_path' => $path,
                'order' => $index,
                'alt_text' => $tour->name . ' - Image ' . ($index + 1),
            ]);
        }

        return redirect()->back()->with('success', 'Đã tải ảnh lên thành công.');
    }

    public function setPrimary(Tour $tour, TourImage $image)
    {
        $tour->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);
        
        return redirect()->back()->with('success', 'Ảnh chính đã được cập nhật.');
    }

    public function destroy(Tour $tour, TourImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        
        return redirect()->back()->with('success', 'Đã xóa ảnh thành công.');
    }
}