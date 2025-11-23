<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTourRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
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
            'status' => 'required|in:active,inactive,sold_out',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'featured' => 'nullable|boolean',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:tour_images,id',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên tour không được để trống',
            'destination_id.required' => 'Vui lòng chọn điểm đến',
            'price_adult.required' => 'Giá người lớn không được để trống',
            'price_child.required' => 'Giá trẻ em không được để trống',
            'price_infant.required' => 'Giá em bé không được để trống',
            'duration_days.required' => 'Số ngày không được để trống',
            'max_people.required' => 'Số người tối đa không được để trống',
        ];
    }
}
