<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use App\Enums\ApplicationTypes;
use App\Enums\LicenceActions;
use App\Enums\LicenceStatus;
use Carbon\Carbon;
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
    public static $action2TypeId = [
      LicenceActions::release->value => ApplicationTypes::ReleaseDetained->value,
      LicenceActions::renew->value => ApplicationTypes::RenewLicence->value,
      LicenceActions::lost->value => ApplicationTypes::LostReplacement->value,
      LicenceActions::damaged->value => ApplicationTypes::DamagedReplacement->value,
    ];
    public static function applicationExists(Licence $licence, int $typeId){
      return 
      LicenceOperationApplication
        ::where('licence_id', $licence['id'])
        ->where('application_type_id', $typeId)
        ->whereHas('application', function($q){
          $q->where('status', '=',ApplicationStatus::New->value)
          ->orWhere('status', '=', ApplicationStatus::Pending->value);
      })
      ->exists();
    }
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
    public function isDetained(){
      return $this['status'] === LicenceStatus::detained->value;
    }
    public function isDeactivated(){
      return $this['status'] === LicenceStatus::deactivated->value;
    }
    public function isExpired(){
      $expiryDate = Carbon::parse($this['expiry_date']);
      return now()->gt($expiryDate);
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
