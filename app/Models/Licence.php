<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Licence extends Model
{
    /** @use HasFactory<\Database\Factories\LicenceFactory> */
    use HasFactory;
    public static $searchRoutes = [
      'find'   => 'licence.find',
      'filter' => 'licence.filter',
    ];
    public static function numericKeys(){
      return 
      collect(self::$columns)->only('ID', 'Licence number')->toArray();
    }
    public static $columns = [
      'ID'             => 'id',
      'Licence number' => 'licence_number',
      'Class'          => 'title',
      'Issue Reason'   => 'issue_reason',
      'Issue Date'     => 'issue_date',
      'Expiry Date'    => 'expiry_date',
      'Status'         => 'status',
    ];
    public static function searchBy(){
      return collect(Self::$columns)->except('Class')->toArray();
    }
    public static function generateNumber(){
      $year = date('Y');
      $count = self::whereYear('created_at', $year)->count() + 1;
      $pad = str_pad($count, 5, '0', STR_PAD_LEFT);
      return "LIC-{$year}-{$pad}";
    }
    public function driver(){
      return $this->belongsTo(Driver::class);
    }
    public function person(){
      return $this->belongsTo(Person::class);
    }
    public function local_licence(){
      return $this->belongsTo(LocalLicence::class);
    }
    public function licence_class(){
      return $this->belongsTo(LicenceClass::class);
    }
    public function driverExists(){
      return $this->driver()->exists();
    }
}
