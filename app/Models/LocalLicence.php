<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use App\Global\BaseQuery;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalLicence extends Model
{
    /** @use HasFactory<\Database\Factories\LocalLicenseFactory> */
    use HasFactory;

    public static $columns = [
      'Local licence application ID' => 'licence_id',
      'Person ID' => 'person_id', 
      'Person name' => 'name', 
      'Person national no' => 'national_no',
      'Licence Class ID' => 'licence_class_id', 
      'Licence Class' => 'licence_class', 
      'Application ID' => 'application_id', 
      'Passed tests' => 'passedTests',
      'Status' => 'status',
    ];
    public static $searchRoutes = ['find'=>'localLicence.find', 'filter'=>'localLicence.filter'];
    public static function numericKeys() {
      return collect(self::$columns)->only('Local licence application ID', 'Person ID', 'Application ID', 'licence_class_id')->toArray();
    }
    public static function searchBy_desired(){
      return collect(self::$columns)->only('Licence ID', 'Person ID', 'Status')->toArray();
    }
    public static function searchBy(){
     return [
      'Local licence application ID' => 'local_licences.id',
      'Person ID' => 'people.id', 
      'Person name' => 'people.name', 
      'Person national no' => 'people.national_no',
      'Licence Class ID' => 'local_licences.licence_class_id', 
      'Licence Class' => 'licence_classes.title', 
      'Application ID' => 'applications.id', 
      'Passed tests' => '',
      'Status' => 'status',
      ];
    }
    public static function isUniqueApplication(int $person_id, int $class_id){
      $exists = LocalLicence::
      join('applications', 'applications.id', '=', 'local_licences.application_id')
      ->where('local_licences.person_id', $person_id)
      ->where('local_licences.licence_class_id', $class_id)
      ->where(function($query){
        $query->where('applications.status', ApplicationStatus::New->value)
              ->orWhere('applications.status', ApplicationStatus::Completed->value);
      })
      ->exists();
      return !$exists;
    }
    public function passedTests(){
      return BaseQuery::passedTests($this['id']);
    }
    public function testIsPassed(int $testTypeId){
      return TestAppointment::testIsPassed($this, $testTypeId);
    }
    public function licenceClass(){
      return $this->belongsTo(LicenceClass::class);
    }
    public function person(){
      return $this->belongsTo(Person::class);
    }
    public function licence_issued(){
      $this->load('application');
      $application_completed = $this['application']['status'] === ApplicationStatus::Completed->value;
      $licence_exists_with_class = 
      Licence::
      where('licence_class_id', $this['licence_class_id'])
      ->where('person_id', $this['person_id'])
      ->exists(); 
      return $application_completed && $licence_exists_with_class; 
    } 
    public function application(){
      return $this->belongsTo(Application::class);
    }
    protected $casts = [
      'created_at' => 'date:Y-m-d',
    ];
}
