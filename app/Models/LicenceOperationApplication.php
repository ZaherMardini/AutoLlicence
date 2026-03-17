<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use App\Enums\ApplicationTypes;
use Illuminate\Database\Eloquent\Model;

class LicenceOperationApplication extends Model
{
  public function application(){
    return $this->belongsTo(Application::class);
  }
  public static function getApplication(Licence $licence, int $application_typeId){
    return      
      LicenceOperationApplication
        ::where('licence_id', $licence['id'])
        ->where('application_type_id', $application_typeId)
        ->whereHas('application', function($q){
          $q->where('status', ApplicationStatus::New->value)
          ->orWhere('status', ApplicationStatus::Pending->value);
        })
        ->with('application:id,status')
        ->firstOrFail();
  }
  public static function completeApplication(Licence $licence, int $application_typeId){
    $application = LicenceOperationApplication::getApplication($licence, $application_typeId);
    $application = $application['application'];
    $application->update(['status' => ApplicationStatus::Completed->value]);
    return $application['id'];
  }
}
