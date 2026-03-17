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
      $licence = $this->service->store($localLicence, $request);
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
    $licence = $this->service->detainRelease($licence, $request);
    $licence->load(['person:id,name', 'licence_class:id,title']);
    return redirect()->route('licence.operations', compact('licence'));
  }
  public function find(Request $request){
    $licence = $this->service->find($request);
    return response()->json($licence);
  }
  public function filter(Request $request){
    $licences = $this->service->filter($request);
    return response()->json($licences);
  }
  public function createOperationApplication(Licence $licence, ApplicationType $applicationType, StoreLicenceServiceRequest $request){
    $this->service->createLicenceOperationApplication($licence, $applicationType);
    return redirect()->route('licence.operations', ['licence' => $licence['id']]);
  }

  public function renew(Licence $licence, RenewLicenceRequest $request){
    $this->service->renew($licence, $request);
    return redirect()->route('licence.operations', ['licence' => $licence['id']]);
  }
  public function replace(Licence $old_licence, ReplaceLicenceRequest $request){
    $newLicenceId = $this->service->replace($old_licence, $request);
    return redirect()->route('licence.operations', ['licence' => $newLicenceId]);
  }
}
