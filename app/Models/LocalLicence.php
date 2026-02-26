<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalLicence extends Model
{
    /** @use HasFactory<\Database\Factories\LocalLicenseFactory> */
    use HasFactory;

    public static $columns = [
      'Licence ID' => 'licence_id',
      'Person ID' => 'person_id', 
      'Person name' => 'name', 
      'Person national no' => 'national_no',
      'Licence Class ID' => 'licence_class_id', 
      'Licence Class' => 'licence_class', 
      'Application ID' => 'application_id', 
      'Passed tests' => '',
      'Status' => 'status',
      'Options' => 'testWithLicenceID',
    ];
    public static $searchRoutes = ['find'=>'LocalLicence.find', 'filter'=>'LocalLicence.filter'];
    public static function numericKeys() {
      return collect(self::$columns)->only('Licence ID', 'Person ID', 'Application ID', 'licence_class_id')->toArray();
    }
    public static function searchBy_desired(){
      return collect(self::$columns)->only('Licence ID', 'Person ID', 'Status')->toArray();
    }
    public static function searchBy(){
     return [
      'Licence ID' => 'local_licences.id',
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
    public function licenceClass(){
      return $this->belongsTo(LicenceClass::class);
    }
    public function person(){
      return $this->belongsTo(Person::class);
    }
    protected $casts = [
      'created_at' => 'date:Y-m-d',
    ];
}
