<?php

namespace App\Http\Requests;

use App\Enums\ApplicationTypes;
use App\Enums\LicenceActions;
use App\Models\Licence;
use Illuminate\Foundation\Http\FormRequest;

class DetainLicenceRequest extends FormRequest
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
        'licence_action' => ['required'],
        'licence_id' => ['required', 'exists:licences,id'],
        'detain_application_id' => ['required', 'exists:applications,id'],
      ];
    }
    public function withValidator($validator){
      $validator->after(function($validator){
        if($validator->errors()->has('licence_action')){
          return;
        }
        $isDetainAction = $this->input('licence_action') === LicenceActions::detain->value;
        $isReleaseAction = $this->input('licence_action') === LicenceActions::release->value;
        $licence = Licence::findOrFail($this->input('licence_id'));
        $errorMessage = '';
        if($this->input('detain_application_id')){

        }
        if($isDetainAction && $licence->isDetained()){
          $errorMessage = 'This licence is already detained.';
        }
        if($isReleaseAction && !$licence->isDetained()){
          $errorMessage = 'This licence is already active.';
        }
        if($licence->isExpired()){
          $errorMessage = 'Cannot detain or release an expired licence, please renew it.';
        }
        if($errorMessage){
          $validator->errors()->add(
            'licence_action',
            $errorMessage
          );
        }
      });
    }
}
