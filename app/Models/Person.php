<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Person extends Model
{
  use HasFactory;

  public static $columns = [
    'ID' => 'id',
    'Name' => 'name',
    'Phone' => 'phone',
    'Email' => 'email',
    'Gender' => 'gender',
    'National No' => 'national_no',
    'Address' => 'address',
    'Date of birth' => 'date_of_birth',
  ];
  public static function numericKeys(){
    return collect(self::$columns)->only('ID')->toArray(); 
  }
  public static $searchRoutes = [
    'find' => 'person.find',
    'filter' => 'person.filter'
    ];
  public function age(){
    return date_diff(now(), $this['date_of_birth'])->y;
  }
  public function isDriver(){
    return Self::driver()->exists();
  }
  public function driver(){
    return $this->hasOne(Driver::class);
  }
  public function inAllowedAge(int $min){
    return $this->age() >= $min;
  }
  public static function searchBy()
    {
      return collect(self::$columns)
          ->except(['Gender', 'Address', 'Date of birth'])
          ->toArray();
    }
    protected $guarded = [];
  
  public function applications(){
    return $this->hasMany(Application::class);
  }
  public function localLicenceApplication(){
    return $this->hasMany(LocalLicence::class);
  }
  public function deleteImage(): void
{
  if (
      $this->image_path &&
      !str_starts_with($this->image_path, 'images/defaults/') &&
      Storage::disk('public')->exists($this->image_path)
  ) {
      Storage::disk('public')->delete($this->image_path);
  }
}
protected $casts = ['date_of_birth' => 'date:Y-m-d'];
}
