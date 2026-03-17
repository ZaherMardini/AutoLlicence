<?php

namespace App\Http\Requests;

use App\Models\Licence;
use App\Models\LicenceOperationApplication;
use App\Rules\LicenceOperatisonRules;
use Illuminate\Foundation\Http\FormRequest;

class ReplaceLicenceRequest extends FormRequest
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
        'licence_id' => ['required', 'exists:licences,id'],
        'licence_replacement_service' => ['required'],
      ];
    }
    public function withValidator($validator){
      $validator->after(function($validator){
        if($validator->errors()->has('licence_replacement_service')){
            return;
        }
        $licence = Licence::findOrFail($this->input('licence_id'));
        if($licence->isDeactivated()){
          return LicenceOperatisonRules::deactivatedLicenceCase($validator, 'licence_replacement_service');
        }
        if($licence->isDetained()){
          return $validator->errors()->add('licence_replacement_service', 'Release licence first');
        }
        $typeId = Licence::$action2TypeId[ $this['licence_replacement_service'] ];
        $this['licence_service'] = $typeId;
        LicenceOperatisonRules::operationApplicationExists($this, $validator, $licence, 'licence_replacement_service');
      });
    }
}
