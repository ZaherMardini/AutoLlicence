<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeTestRequest extends FormRequest
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
      dd($this);
        return [
          'test_type_id' => ['required', 'exists:test_types,id'],
          'test_appointment_id' => ['required', 'exists:test_appointments,id'],
          'created_by_user_id' => ['required', 'exists:users,id'],
          'result' => ['required', 'boolean'],
          'notes' => ['required', 'string', 'min:3', 'max:20'], 
        ];
    }
}
