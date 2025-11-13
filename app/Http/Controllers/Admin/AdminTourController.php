<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Tour;
use Illuminate\Http\Request;
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
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'destination_id' => 'required|exists:destinations,id',
                'short_description' => 'required|string|max:500',
                'full_description' => 'required|string',
                'itinerary' => 'required|string',
                'price_adult' => 'required|numeric|min:0',
                'price_child' => 'required|numeric|min:0',
                'price_infant' => 'required|numeric|min:0',
                'duration_days' => 'required|integer|min:1|max:365',
                'duration_nights' => 'required|integer|min:0|max:364',
                'max_people' => 'required|integer|min:1|max:1000',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after:start_date',
                'status' => 'required|in:active,inactive,sold_out',
                'thumbnail' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
                'featured' => 'nullable|boolean',
                'images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            ], [
                'name.required' => 'Tên tour không được để trống',
                'destination_id.required' => 'Vui lòng chọn điểm đến',
                'destination_id.exists' => 'Điểm đến không tồn tại',
                'short_description.required' => 'Mô tả ngắn không được để trống',
                'full_description.required' => 'Mô tả đầy đủ không được để trống',
                'itinerary.required' => 'Lịch trình không được để trống',
                'price_adult.required' => 'Giá người lớn không được để trống',
                'price_adult.numeric' => 'Giá người lớn phải là số',
                'price_adult.min' => 'Giá người lớn phải lớn hơn 0',
                'price_child.required' => 'Giá trẻ em không được để trống',
                'price_child.numeric' => 'Giá trẻ em phải là số',
                'price_child.min' => 'Giá trẻ em phải lớn hơn 0',
                'price_infant.required' => 'Giá em bé không được để trống',
                'price_infant.numeric' => 'Giá em bé phải là số',
                'price_infant.min' => 'Giá em bé phải lớn hơn 0',
                'duration_days.required' => 'Số ngày không được để trống',
                'duration_days.integer' => 'Số ngày phải là số nguyên',
                'duration_days.min' => 'Số ngày ít nhất là 1',
                'duration_days.max' => 'Số ngày tối đa là 365',
                'duration_nights.required' => 'Số đêm không được để trống',
                'duration_nights.integer' => 'Số đêm phải là số nguyên',
                'duration_nights.min' => 'Số đêm ít nhất là 0',
                'max_people.required' => 'Số người tối đa không được để trống',
                'max_people.integer' => 'Số người phải là số nguyên',
                'max_people.min' => 'Số người ít nhất là 1',
                'max_people.max' => 'Số người tối đa là 1000',
                'start_date.required' => 'Ngày bắt đầu không được để trống',
                'start_date.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi',
                'end_date.required' => 'Ngày kết thúc không được để trống',
                'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu',
                'thumbnail.required' => 'Vui lòng chọn ảnh đại diện',
                'thumbnail.image' => 'Ảnh đại diện phải là file hình ảnh',
                'thumbnail.max' => 'Ảnh đại diện không được vượt quá 5MB',
            ]);

            // Handle featured checkbox
            $validated['featured'] = $request->has('featured') ? true : false;

            // Upload thumbnail first
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

            return redirect()
                ->route('admin.tours.index')
                ->with('success', 'Tạo tour thành công! Tour "' . $tour->name . '" đã được thêm vào hệ thống.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            \Log::error('Error creating tour: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
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
    public function update(Request $request, Tour $tour)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'destination_id' => 'required|exists:destinations,id',
                'short_description' => 'required|string|max:500',
                'full_description' => 'required|string',
                'itinerary' => 'required|string',
                'price_adult' => 'required|numeric|min:0',
                'price_child' => 'required|numeric|min:0',
                'price_infant' => 'required|numeric|min:0',
                'duration_days' => 'required|integer|min:1|max:365',
                'duration_nights' => 'required|integer|min:0|max:364',
                'max_people' => 'required|integer|min:1|max:1000',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after:start_date',
                'status' => 'required|in:active,inactive,sold_out',
                'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
                'featured' => 'nullable|boolean',
                'delete_images' => 'nullable|array',
                'delete_images.*' => 'exists:tour_images,id',
                'images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            ], [
                'name.required' => 'Tên tour không được để trống',
                'destination_id.required' => 'Vui lòng chọn điểm đến',
                'price_adult.required' => 'Giá người lớn không được để trống',
                'price_child.required' => 'Giá trẻ em không được để trống',
                'price_infant.required' => 'Giá em bé không được để trống',
                'duration_days.required' => 'Số ngày không được để trống',
                'max_people.required' => 'Số người tối đa không được để trống',
            ]);

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
                        // Delete file from storage
                        Storage::disk('public')->delete($image->image_path);
                        // Delete database record
                        $image->delete();
                    }
                }
            }

            // Handle uploading new images
            if ($request->hasFile('images')) {
                // Get the current max order
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

            return redirect()
                ->route('admin.tours.index')
                ->with('success', 'Cập nhật tour "' . $tour->name . '" thành công!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            \Log::error('Error updating tour: ' . $e->getMessage());
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
