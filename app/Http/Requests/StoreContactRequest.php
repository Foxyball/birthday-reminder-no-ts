<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $contactId = $this->route('contact')?->id;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:contacts,email,'.$contactId,
            'phone' => 'nullable|string|max:255',
            'birthday_day' => 'required|numeric|min:1|max:31',
            'birthday_month' => 'required|numeric|min:1|max:12',
            'birthday_year' => 'nullable|integer|min:1900|max:'.now()->year,
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'notes' => 'nullable|string|max:1000',
            'gift_ideas' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('messages.name')]),
            'email.required' => __('validation.required', ['attribute' => __('messages.email')]),
            'email.email' => __('validation.email', ['attribute' => __('messages.email')]),
            'email.unique' => __('validation.unique', ['attribute' => __('messages.email')]),
            'image.image' => __('validation.image', ['attribute' => __('messages.image')]),
            'image.mimes' => __('validation.mimes', ['attribute' => __('messages.image'), 'values' => 'jpeg, png, jpg, gif, webp']),
            'image.max' => __('validation.max.file', ['attribute' => __('messages.image'), 'max' => '5MB']),
        ];
    }
}
