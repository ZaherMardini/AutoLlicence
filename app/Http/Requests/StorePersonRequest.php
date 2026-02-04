<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonRequest extends FormRequest
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
        'name' => ['required', 'string', 'min:3', 'max:20'],
        'email' => ['email', 'unique:people,email'],
        'phone' => ['string'],
        'national_no' => ['string', 'unique:people,national_no'],
        'address' => ['string'],
        'gender' => ['required'],
        'file' => ['image', 'mimes:png,jpg'],
        'country_id' => ['required', 'exists:countries,id'],
        'date_of_birth' => ['date'],
      ];
    }
}
