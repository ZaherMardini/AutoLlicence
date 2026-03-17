<?php

namespace App\Http\Requests;

use App\Enums\ApplicationStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApplicationRequest extends FormRequest
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
        'person_id' => ['required', 'exists:people,id'],
        'application_type_id' => ['required', 'exists:application_types,id',
          Rule::unique('applications','application_type_id')
          ->where('person_id', $this['person_id'])
          ->where('status', ApplicationStatus::New->value),
        ],
      ];
    }
}
