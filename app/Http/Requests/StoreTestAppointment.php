<?php

namespace App\Http\Requests;

use App\Models\LocalLicence;
use App\Models\TestAppointment;
use Illuminate\Foundation\Http\FormRequest;

class StoreTestAppointment extends FormRequest
{
    public function authorize(): bool
    {
      return true;
    }

  public function rules(): array
  {
    return [
      'local_licence_id' => ['required', 'exists:local_licences,id'],
      'person_id' => ['required', 'exists:people,id'],
      'appointment_date' => ['required', 'date'],
      'test_type_id' => ['required', 'exists:test_types,id']
    ];
  }
  public function withValidator($validator){
    $validator->after(function($validator){
      if($validator->errors()->has('person_id')){
        return;
      }
      $personId = $this->input('person_id');
      $local_licence_id = $this->input('local_licence_id');
      $local_licence = LocalLicence::findOrFail($local_licence_id);
      $testTypeId = $this->input('test_type_id');
      // $isUniqueApplication = TestAppointment::isUniqueApplication($personId, $local_licence_id, $testTypeId);
      $previousActiveAppointment = TestAppointment::activeAppointmentExists($personId);
      $testIsPassed = TestAppointment::testIsPassed($local_licence, $testTypeId);
      if ($previousActiveAppointment) {
        $validator->errors()->add(
          'person_id',
          'This person already has an active appointment for this test.'
        );
      }
      if($testIsPassed){
        $validator->errors()->add(
          'person_id',
          'This person has already passed this test.'
        );
      }
    });
  }
}
