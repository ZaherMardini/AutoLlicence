<?php

namespace App\Models;

use App\Global\BaseQuery;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestAppointment extends Model
{
  /** @use HasFactory<\Database\Factories\TestAppointmentFactory> */
  use HasFactory;
  public function test_type(){
    return $this->belongsTo(TestType::class);
  }
  public static $searchRoutes = ['find' => 'appointments.find'];
  public static $columns = [
    'ID' => 'id',
    'Test Type' => 'test_type',
    'Licence class' => 'class',
    'Appointment Date' => 'appointment_date',
    'Paid fees' => 'paid_fees'
  ];
  public static function paidFees(int $testTypeId, int $applicationTypeId){
      return TestType::find($testTypeId)['fees'] + ApplicationType::find($applicationTypeId)['fees'];
  }
  public static function isUniqueApplication(int $personId, int $local_licence_id, int $testTypeId){
    $result = TestAppointment::
    where('person_id', $personId)->
    where('test_type_id', $testTypeId)->
    where('local_licence_id', $local_licence_id)->
    where('isLocked', false)
    ->exists();
    return !$result;
  }
  public static function activeAppointmentExists(int $personId){
    return TestAppointment::where('person_id', $personId)->where('isLocked', false)->exists();
  }
  public function test(){
    return $this->hasOne(Test::class);
  }
  public static function testPassed(LocalLicence $localLicence){
    dd('not implemented');
  }
  public function testtrials(){
    return BaseQuery::testTrials($this);
  }
}
