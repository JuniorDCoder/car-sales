<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'make' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:'.(date('Y') + 1),
            'price' => 'required|integer|min:0',
            'mileage' => 'nullable|integer|min:0',
            'fuel_type' => 'required|in:Petrol,Diesel,Electric,Hybrid',
            'transmission' => 'required|in:Manual,Automatic',
            'body_type' => 'required|in:Sedan,SUV,Pickup,Hatchback,Coupe,Van,Minivan',
            'color' => 'required|string|max:50',
            'condition' => 'required|in:New,Used,Certified Pre-Owned',
            'description' => 'required|string|min:20',
            'features' => 'nullable|array',
            'features.*' => 'string|max:100',
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'string',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_featured' => 'boolean',
            'is_sold' => 'boolean',
        ];
    }
}
