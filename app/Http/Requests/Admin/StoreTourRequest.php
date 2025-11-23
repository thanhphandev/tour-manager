<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreTourRequest extends FormRequest
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
            'thumbnail' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
            'featured' => 'nullable|boolean',
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
            'thumbnail.required' => 'Vui lòng chọn ảnh đại diện',
            'thumbnail.image' => 'Ảnh đại diện phải là file hình ảnh',
            'thumbnail.max' => 'Ảnh đại diện không được vượt quá 5MB',
        ];
    }
}
