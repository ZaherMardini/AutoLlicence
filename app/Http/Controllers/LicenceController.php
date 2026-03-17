<?php

namespace App\Http\Controllers;

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
use App\Http\Requests\StoreApplicationRequest;
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
use App\Services\LicenceService;
use Carbon\Carbon;
use Illuminate\Container\Attributes\Auth as AttributesAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LicenceController extends Controller
{
  protected $service;
  public function __construct(LicenceService $service){
    $this->service = $service;
  }
  public function store(LocalLicence $localLicence, StoreLicenceRequest $request){
    $info = $request->validated();
    $licence = DB::transaction(function() use($info, $localLicence) {
      $localLicence->load(['person', 'licenceClass']);
      $driver = Driver::create(['person_id' => $localLicence['person_id']]);
      $info['driver_id'] = $driver['id'];
      $info['licence_number'] = Licence::generateNumber();
      $info['status'] = LicenceStatus::new->value;
      $info['image'] = $localLicence['person']['image_path'];
      $info['licence_class_id'] = $localLicence['licenceClass']['id'];
      $info['issue_date'] = now();
      $info['issue_reason'] = LicenceIssueReasons::new->value;
      $issue_date = Carbon::parse($info['issue_date']);
      $info['expiry_date'] = $issue_date->addYears($localLicence['licenceClass']['valid_years']);
      $licence = Licence::create($info);
      $licence->load(['local_licence','person']);
      $local_licence = LocalLicence::
      where('person_id', $licence['person_id'])
      ->where('licence_class_id', $licence['licence_class_id'])
      ->first();
      Application::find($local_licence['application_id'])->update(['status' => ApplicationStatus::Completed->value]);
      return $licence;
      });
      return redirect()->route('licence.show', compact('licence'));
      }
  public function show(Licence $licence){
    $licence->load(['person:id,name', 'licence_class:id,title']);
    $licences = Licence::where('person_id', $licence['person']['id'])
    ->with(['person:id,name', 'licence_class:id,title'])->get();
    foreach($licences as $licence){
      $licence['title'] = $licence['licence_class']['title'];
    }
    session(['person_id' => $licence['person']['id']]);
    $columns = Licence::$columns;
    $searchBy = Licence::searchBy();
    $routes = Licence::$searchRoutes;
    $menu = Menus::licenceOperations($licence['id']);
    $release = ApplicationTypes::ReleaseDetained->value;
    $damaged = ApplicationTypes::DamagedReplacement->value;
    $lost = ApplicationTypes::LostReplacement->value;
    $renew = ApplicationTypes::RenewLicence->value;

    return view('licence.show', 
    compact( 'licences','licence', 'columns', 'routes', 'searchBy', 'menu',
    'release', 'damaged', 'lost', 'renew'
    ));
  }
  public function operations(Licence $licence){
    $licence->load(['person:id,name', 'licence_class:id,title']);
    $services = ApplicationType::get();
    $services = $services->keyBy('id');
    $fines = Fine::get();
    $fines = $fines->keyBy('id');
    return view('licence.operations', compact('licence', 'services', 'fines'));
  }
  public function detainRelease(Licence $licence, DetainReleaseLicenceRequest $request){
    $info = $request->validated();
    $action = $info['licence_action'];
    if($action === LicenceActions::detain->value){
      DB::transaction(function() use($licence){
        $licence->update(['status' => LicenceStatus::detained->value]);
        DetainedLicence::create([
          'licence_id' => $licence['id'],
          'created_by_user_id' => Auth::id(),
        ]);
      });
    }
    else if($action === LicenceActions::release->value){
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
    $licence->load(['person:id,name', 'licence_class:id,title']);
    return redirect()->route('licence.operations', compact('licence'));
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
    return response()->json($licence);
  }
  public function filter(Request $request){
    $licences = null;
    $licences = Licence::where('person_id', session('person_id'))
    ->with(['licence_class:id,title', 'person:id,name']);
    $licences = Methods::filter($licences, $request, Licence::searchBy(), Licence::numericKeys());
    foreach ($licences as $licence) {
      $licence['title'] = $licence['licence_class']['title'];
    }
    return response()->json($licences);
    }
  public function createOperationApplication(Licence $licence, ApplicationType $applicationType, StoreLicenceServiceRequest $request){
    $this->service->createLicenceOperationApplication($licence, $applicationType);
    return redirect()->route('applications.index');
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
    return redirect()->route('applications.index');
  }
  public function replace(Licence $old_licence, ReplaceLicenceRequest $request){
    $attributes = $old_licence->toArray();
    $info = $request->validated();
    unset($attributes['id']);
    $attributes['issue_reason'] = "Replacement for {$info['licence_replacement_service']} licence"; 
    // dd([$old_licence->toArray(), $attributes, $request->validated()]);
    Licence::create($attributes);
    $old_licence->update(['status' => LicenceStatus::deactivated->value]);
    $typeId = -1;
    if($info['licence_replacement_service'] === 'lost'){
      $typeId = ApplicationTypes::LostReplacement->value;
    }
    else{
      $typeId = ApplicationTypes::DamagedReplacement->value;
    }
    LicenceOperationApplication::completeApplication($old_licence, $typeId);
    return redirect()->route('applications.index');
  }
}
