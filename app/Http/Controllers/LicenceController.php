<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStatus;
use App\Enums\LicenceStatus;
use App\Global\Current;
use App\Global\Methods;
use App\Http\Requests\StoreLicenceRequest;
use App\Models\Application;
use App\Models\Driver;
use App\Models\Licence;
use App\Models\LocalLicence;
use App\Models\Person;
use Carbon\Carbon;
use Database\Seeders\LocalLicenceSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LicenceController extends Controller
{
  public function baseQuery(){
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
    return view('licence.show', 
    compact( 'licences','licence', 'columns', 'routes', 'searchBy'));
  }
  public function operations(){
    return view('licence.operations');
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
      // dd($licence);
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
}
