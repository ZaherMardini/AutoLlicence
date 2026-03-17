<?php

namespace App\Global;

use App\Models\Application;
use App\Models\LocalLicence;
use App\Models\Test;
use App\Models\TestAppointment;

class BaseQuery{
  public static function applications(){
    return 
    Application
    ::join('people', 'applications.person_id', 'people.id')
    ->join('application_types', 'applications.application_type_id', 'application_types.id')
    ->select(
      'applications.id as id',
      'people.id as person_id',
      'people.name as person_name',
      'application_types.title as service',
      'application_types.id as service_id',
      'application_types.fees as fees',
      'applications.status as status',
    );
  }
  public static function testAppointments(){
    return
    TestAppointment
    ::join('test_Types', 'test_Types.id', '=', 'test_appointments.test_Type_id')
    ->leftJoin('tests', 'tests.test_appointment_id', 'test_appointments.id')
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
      'test_appointments.isLocked',
      'tests.result'
    );
  }
  public static function passedTests(int $localLicenceId){
    return LocalLicence::
    join('test_appointments', 'test_appointments.local_licence_id', 'local_licences.id')
    ->join('tests', 'tests.test_appointment_id', 'test_appointments.id')
    ->where('local_licences.id', $localLicenceId)
    ->where('test_appointments.isLocked', 1)
    ->where('tests.result', 1)
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
  public static function testIsPassed(LocalLicence $localLicence, $testTypeId){
    return TestAppointment::join('tests', 'test_appointments.id', '=', 'tests.test_appointment_id')
    ->where('test_appointments.person_id', $localLicence['person_id'])
    ->where('test_appointments.local_licence_id', $localLicence['id'])
    ->where('tests.test_type_id', $testTypeId)
    ->where('tests.result', '1')
    ->exists();
  }
  public static function simple_test_Passed(TestAppointment $appointment){
    return Test::join('test_appointments', 'test_appointments.id', 'tests.test_appointment_id')
    ->where('test_appointments.person_id', $appointment['person_id'])
    ->where('test_appointments.local_licence_id', $appointment['id'])
    ->where('test_appointments.test_type_id', $appointment['test_type_id'])
    ->where('tests.result', 1)
    ->exists();
  }
}