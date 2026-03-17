<?php

namespace App\Http\Requests;

use App\Enums\ApplicationStatus;
use App\Enums\ApplicationTypes;
use App\Enums\LicenceActions;
use App\Models\Licence;
use App\Models\LicenceOperationApplication;
use App\Models\ReleaseLicenceApplication;
use App\Rules\LicenceOperatisonRules;
use Illuminate\Foundation\Http\FormRequest;

class DetainReleaseLicenceRequest extends FormRequest
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
      ];
    }
    public function withValidator($validator){
      $validator->after(function($validator){
        if($validator->errors()->has('licence_action')){
          return;
        }
        $isDetainOption = $this->input('licence_action') === LicenceActions::detain->value;
        $isReleaseOption = $this->input('licence_action') === LicenceActions::release->value;
        $licence = Licence::findOrFail($this->input('licence_id'));
        if($licence->isDeactivated()){
          return LicenceOperatisonRules::deactivatedLicenceCase($validator, 'licence_action');
        }
        $isDetainedLicence = $licence->isDetained();
        $errorMessage = '';
        if($isDetainOption && $isDetainedLicence){
          $errorMessage = 'This licence is already detained.';
        }
        if($isReleaseOption && !$isDetainedLicence){
          $errorMessage = 'This licence is already active.';
        }
        if($isReleaseOption && $isDetainedLicence){
          $releaseApplicationExists = Licence::applicationExists($licence, ApplicationTypes::ReleaseDetained->value);
          if(!$releaseApplicationExists){
            $errorMessage = 'You need an application for this service.';
          }
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
