<?php

namespace App\Http\Requests;

use App\Enums\ApplicationTypes;
use App\Models\Application;
use App\Models\LocalLicence;
use Illuminate\Foundation\Http\FormRequest;

class StoreLocalLicenceRequest extends FormRequest
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
        'licence_class_id' => ['required', 'exists:licence_classes,id'],
        'person_id' => ['required', 'exists:people,id'],        
      ];
    }

  public function withValidator($validator)
    {
      $validator->after(function ($validator) {
        if($validator->errors()->has('person_id')){
          return;
        }
        $personId = $this->input('person_id');
        $classId = $this->input('licence_class_id');
        $isUniqueApplication = LocalLicence::isUniqueApplication($personId, $classId);
        if (! $isUniqueApplication) {
          $validator->errors()->add(
            'person_id',
            'This person already has an active or completed application for this service.'
          );
        }
    });
}
}
