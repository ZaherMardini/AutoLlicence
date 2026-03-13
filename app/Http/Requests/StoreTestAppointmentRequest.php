<?php

namespace App\Http\Requests;

use App\Models\LocalLicence;
use App\Models\TestAppointment;
use App\Rules\TestAppointmentRules as RulesTestAppointment;
use App\Rules\TestAppointmentRules;
use Illuminate\Foundation\Http\FormRequest;

class StoreTestAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
      return true;
    }

  public function rules(): array
  {
    return RulesTestAppointment::baseRuels();
  }
  public function withValidator($validator){
    $validator->after(function($validator){
      TestAppointmentRules::afterValidation($this, $validator);
    });
  }
}
