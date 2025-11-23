<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
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
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'start_date' => 'required|date|after_or_equal:today',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'infants' => 'nullable|integer|min:0',
            'special_requests' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'adults.required' => 'Vui lòng nhập số lượng người lớn.',
            'adults.min' => 'Phải có ít nhất 1 người lớn tham gia tour.',
            'phone.required' => 'Vui lòng nhập số điện thoại liên hệ.',
        ];
    }
    
    public function totalPeople(): int
    {
        return ($this->adults ?? 0) + ($this->children ?? 0) + ($this->infants ?? 0);
    }
}
