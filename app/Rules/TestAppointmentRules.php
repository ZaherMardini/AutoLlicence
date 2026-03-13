<?php 
namespace App\Rules;

use App\Models\LocalLicence;
use App\Models\TestAppointment;

class TestAppointmentRules{
  public static function baseRuels(){
      return [
      'local_licence_id' => ['required', 'exists:local_licences,id'],
      'person_id' => ['required', 'exists:people,id'],
      'appointment_date' => ['required', 'date'],
      'test_type_id' => ['required', 'exists:test_types,id']
    ];
  }
  public static function afterValidation($request, $validator){
    if($validator->errors()->has('person_id')){
      return;
    }
      $personId = $request->input('person_id');
      $local_licence_id = $request->input('local_licence_id');
      $local_licence = LocalLicence::findOrFail($local_licence_id);
      $testTypeId = $request->input('test_type_id');
      $previousActiveAppointment = TestAppointment::activeAppointmentExists($personId, $testTypeId);
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
  }
}