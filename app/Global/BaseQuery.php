<?php

namespace App\Global;

use App\Models\TestAppointment;

class BaseQuery{
  public static function testAppointments(){
    return
    TestAppointment
    ::join('test_Types', 'test_Types.id', '=', 'Test_appointments.test_Type_id')
    ->join('people', 'people.id', '=', 'test_appointments.person_id')
    ->join('local_licences', 'test_appointments.local_licence_id', '=', 'local_licences.id')
    ->join('licence_classes', 'licence_classes.id', '=', 'local_licences.licence_class_id')
    ->select(
      'test_appointments.id as id',
      'people.id as person_id',
      'people.name',
      'test_types.title as test_type',
      'licence_classes.title as class',
      'local_licences.id as local_licence_id',
      'test_appointments.appointment_date',
      'test_appointments.paid_fees as paid_fees',
      'test_appointments.isLocked',
    );
  }
  public function passedTests(){
    TestAppointment::join('tests', 'tests.test_appointment_id', 'test_appointments.id')
    ->count();
  }
  public static function testTrials(TestAppointment $appointment):int{
    return TestAppointment
    ::where('local_licence_id', '=', $appointment['local_licence_id'])
    ->where('person_id', '=', $appointment['person_id'])
    ->where('test_type_id', '=', $appointment['test_type_id'])
    ->where('isLocked', '=', 1)
    ->count();
  }
}