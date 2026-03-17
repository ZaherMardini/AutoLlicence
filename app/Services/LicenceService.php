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
  public function detain(Licence $licence){
    DB::transaction(function() use($licence){
      $licence->update(['status' => LicenceStatus::detained->value]);
      DetainedLicence::create([
        'licence_id' => $licence['id'],
        'created_by_user_id' => Auth::id(),
      ]);
    });
  }
  public function release(Licence $licence){
      DB::transaction(function() use($licence){
        $licence->update(['status' => LicenceStatus::new->value]);
        $detained = DetainedLicence::where('licence_id', $licence['id'])->first();
        $releaseApplication = LicenceOperationApplication::getApplication($licence, ApplicationTypes::ReleaseDetained->value);
        $fine = Fine::findOrFail(FineActions::release->value)['ammount'];
        $detained->update([
          'released_by_user_id' => Auth::id(),
          'release_date'        => now(),
          'release_application_id' => $releaseApplication['id'],
          'isReleased'          => true,
          'fine'                => $fine,
          ]);
      });
  }
  public function detainRelease(Licence $licence, DetainReleaseLicenceRequest $request){
    $info = $request->validated();
    $action = $info['licence_action'];
    if($action === LicenceActions::detain->value){
      self::detain($licence);
    }
    else if($action === LicenceActions::release->value){
      self::release($licence);
    }
    return $licence;
  }
  public function filter(Request $request){
    $licences = null;
    $licences = Licence::where('person_id', session('person_id'))
    ->with(['licence_class:id,title', 'person:id,name']);
    $licences = Methods::filter($licences, $request, Licence::searchBy(), Licence::numericKeys());
    foreach ($licences as $licence) {
      $licence['title'] = $licence['licence_class']['title'];
    }
    return $licences;
  }
  public function find(Request $request){
    $searchKey = $request['searchKey'];
    $value = $request['value'];
    $licence = null;
    if(in_array( $searchKey, Licence::searchBy() )){
      $licence = Licence::where('person_id', session('person_id'))
      ->where($searchKey,$value)
      ->with(['licence_class:id,title', 'person:id,name'])
      ->first();
      if($licence){
        $licence['title'] = $licence['licence_class']['title'];
      }
    }
    return $licence;
  }
  public function renew(Licence $licence, RenewLicenceRequest $request){
    $licence->load('licence_class:id,valid_years');
    $currentExpiryDate = $licence['expiry_date'];
    $validYears = $licence['licence_class']['valid_years'];
    $renewedDate = Carbon::parse($currentExpiryDate)->addYears($validYears);
    $licence->update([
      'expiry_date' => $renewedDate,
      'status' => 'Active',
      'issue_reason' => LicenceIssueReasons::renewed->value
    ]);
    LicenceOperationApplication::completeApplication($licence, ApplicationTypes::RenewLicence->value);
  }
  public function replace(Licence $old_licence, Request $request){
    $attributes = $old_licence->toArray();
    $info = $request->validated();
    unset($attributes['id']);
    $attributes['issue_reason'] = "Replacement for {$info['licence_replacement_service']} licence"; 
    $newLicence = Licence::create($attributes);
    $old_licence->update(['status' => LicenceStatus::deactivated->value]);
    $typeId = -1;
    if($info['licence_replacement_service'] === 'lost'){
      $typeId = ApplicationTypes::LostReplacement->value;
    }
    else{
      $typeId = ApplicationTypes::DamagedReplacement->value;
    }
    LicenceOperationApplication::completeApplication($old_licence, $typeId);
    return $newLicence['id'];
  }
}