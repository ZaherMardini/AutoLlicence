<?php
namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Enums\ApplicationTypes;
use App\Enums\FineActions;
use App\Enums\LicenceActions;
use App\Enums\LicenceIssueReasons;
use App\Enums\LicenceStatus;
use App\Global\Menus;
use App\Global\Methods;
use App\Http\Requests\DetainReleaseLicenceRequest;
use App\Http\Requests\RenewLicenceRequest;
use App\Http\Requests\ReplaceLicenceRequest;
use App\Http\Requests\StoreLicenceRequest;
use App\Http\Requests\StoreLicenceServiceRequest;
use App\Models\Application;
use App\Models\ApplicationType;
use App\Models\DetainedLicence;
use App\Models\Driver;
use App\Models\Fine;
use App\Models\Licence;
use App\Models\LicenceOperationApplication;
use App\Models\LocalLicence;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;




class LicenceService{
  public function createLicenceOperationApplication(Licence $licence, ApplicationType $type){
    DB::transaction(function() use($licence, $type){
      $applicaiton = 
      Application::create([
        'application_type_id' => $type['id'],
        'created_by_user' => Auth::id(),
        'person_id' => $licence['person_id'],
        'fees' => $type['base_application_fee'] + $type['fees'], 
        'status' => ApplicationStatus::New->value,
      ]);
      LicenceOperationApplication::
      create([
        'application_id' => $applicaiton['id'],
        'application_type_id' => $type['id'],
        'licence_id' => $licence['id'],
      ]);
    });
  }
  public function store(LocalLicence $localLicence, StoreLicenceRequest $request){
    $info = $request->validated();
    $licence = DB::transaction(function() use($info, $localLicence) {
      $driver = null;
      $localLicence->load(['person', 'licenceClass']);
      $licenceClass = $localLicence['licenceClass'];
      $person = $localLicence['person'];
      if(!$person->isDriver()){
        $driver = Driver::create(['person_id' => $localLicence['person_id']]);
        $info['driver_id'] = $driver['id'];
      }
      else{
        $person->load('driver');
        $info['driver_id'] = $person['driver']['id'];
      }
      $info['licence_number'] = Licence::generateNumber();
      $info['status'] = LicenceStatus::new->value;
      $info['image'] = $person['image_path'];
      $info['licence_class_id'] = $licenceClass['id'];
      $info['issue_date'] = now();
      $info['issue_reason'] = LicenceIssueReasons::new->value;
      $issue_date = Carbon::parse($info['issue_date']);
      $info['expiry_date'] = $issue_date->addYears($licenceClass['valid_years']);
      $licence = Licence::create($info);
      $local_licence = LocalLicence::
      where('person_id', $person['id'])
      ->where('licence_class_id', $licenceClass['id'])
      ->first();
      Application::find($local_licence['application_id'])->update(['status' => ApplicationStatus::Completed->value]);
      return $licence;
      });
      return $licence;
  }
}