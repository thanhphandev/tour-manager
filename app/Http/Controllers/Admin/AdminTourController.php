<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTourRequest;
use App\Http\Requests\Admin\UpdateTourRequest;
use App\Models\Destination;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminTourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tours = Tour::with(['destination', 'primaryImage'])->paginate(10);
        return view('admin.tours.index', compact('tours'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $destinations = Destination::where('is_active', true)->get();
        return view('admin.tours.create', compact('destinations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTourRequest $request)
    {
        try {
            $tour = DB::transaction(function () use ($request) {
                $validated = $request->validated();
                
                // Handle featured checkbox
                $validated['featured'] = $request->has('featured') ? true : false;

                // Upload thumbnail
                if ($request->hasFile('thumbnail')) {
                    $validated['thumbnail'] = $request->file('thumbnail')->store('tours/thumbnails', 'public');
                }

                // Create tour
                $tour = Tour::create($validated);

                // Upload multiple images
                if ($request->hasFile('images')) {
                    $order = 1;
                    foreach ($request->file('images') as $image) {
                        $path = $image->store('tours/images', 'public');
                        $tour->images()->create([
                            'image_path' => $path,
                            'alt_text' => $tour->name,
                            'order' => $order,
                            'is_primary' => $order === 1,
                        ]);
                        $order++;
                    }
                }

                return $tour;
            });

            return redirect()
                ->route('admin.tours.index')
                ->with('success', 'Tạo tour thành công! Tour "' . $tour->name . '" đã được thêm vào hệ thống.');

        } catch (\Exception $e) {
            Log::error('Error creating tour: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo tour: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tour $tour)
    {
        $tour = Tour::with(['destination', 'images'])->findOrFail($tour->id);
        return view('admin.tours.show', compact('tour'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(UpdateTourRequest $request, Tour $tour)
    {
        try {
            DB::transaction(function () use ($request, $tour) {
                $validated = $request->validated();
                
                // Handle featured checkbox
                $validated['featured'] = $request->has('featured') ? true : false;

                // Handle thumbnail upload
                if ($request->hasFile('thumbnail')) {
                    if ($tour->thumbnail) {
                        Storage::disk('public')->delete($tour->thumbnail);
                    }
                    $validated['thumbnail'] = $request->file('thumbnail')->store('tours/thumbnails', 'public');
                }

                $tour->update($validated);

                // Handle deleting old images
                if ($request->has('delete_images') && is_array($request->delete_images)) {
                    foreach ($request->delete_images as $imageId) {
                        $image = $tour->images()->find($imageId);
                        if ($image) {
                            Storage::disk('public')->delete($image->image_path);
                            $image->delete();
                        }
                    }
                }

                // Handle uploading new images
                if ($request->hasFile('images')) {
                    $maxOrder = $tour->images()->max('order') ?? 0;
                    
                    foreach ($request->file('images') as $image) {
                        $path = $image->store('tours/images', 'public');
                        $maxOrder++;
                        $tour->images()->create([
                            'image_path' => $path,
                            'alt_text' => $tour->name,
                            'order' => $maxOrder,
                            'is_primary' => false,
                        ]);
                    }
                }
            });

            return redirect()
                ->route('admin.tours.index')
                ->with('success', 'Cập nhật tour "' . $tour->name . '" thành công!');

        } catch (\Exception $e) {
            Log::error('Error updating tour: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật tour. Vui lòng thử lại sau.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function edit(Tour $tour)
    {
        $destinations = Destination::where('is_active', true)->get();
        return view('admin.tours.edit', compact('tour', 'destinations'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tour $tour)
    {
         if ($tour->thumbnail) {
            Storage::disk('public')->delete($tour->thumbnail);
        }

        $tour->delete();
        return redirect()->route('admin.tours.index')->with('success', 'Xóa tour thành công.');
    }
}
