<?php

namespace App\Http\Controllers;

use App\Enums\CardMode;
use App\Global\BaseQuery;
use App\Http\Requests\StoreLocalLicenceRequest;
use App\Models\Licence;
use App\Models\LocalLicence;
use App\Models\Person;
use App\Services\LocalLicenceService;
use Illuminate\Http\Request;

class LocalLicenceController extends Controller
{
  protected $service;
  public function __construct(LocalLicenceService $service){
    $this->service = $service;
  }
  public function index(){
    return $this->service->index();
  }
  public function show(){
    $searchBy = LocalLicence::searchBy();
    $searchRoutes = LocalLicence::$searchRoutes;

    return view('localLicence.show', compact('searchBy', 'searchRoutes'));
  }
  public function create(){
    $searchBy = Person::searchBy();
    $searchRoutes = Person::$searchRoutes;
    $mode = CardMode::new->value;
    return view('LocalLicence.create', 
    compact('mode','searchBy','searchRoutes'));  
  }
  public function store(StoreLocalLicenceRequest $request){
    return $this->service->store($request);
  }
  public function edit(){
    dd('not implemented');
  }
    public function update(){
    dd('not implemented');
    return view('LocalLicence.create');
  }
  public function filter(Request $request){
    $items = $this->service->filter($request);
    foreach ($items as $item) {
      $item['passedTests'] = BaseQuery::passedTests($item['licence_id']);
    }
    return response()->json($items);
  }
  public function find(Request $request){
    $searchKey = $request['searchKey'];
    $value = $request['value'];
    $localLicence = null;
    if(in_array($searchKey, LocalLicence::searchBy())){
    $localLicence = LocalLicence::where($searchKey,$value)
    ->with(['licenceClass', 'person'])
    ->first(); 
    if($localLicence){
      $localLicence['passedTests'] = BaseQuery::passedTests($localLicence['id']);
      if($localLicence->licence_issued()){
        $result = Licence::
        where('licence_class_id', $localLicence['licence_class_id'])
        ->where('person_id', $localLicence['person_id'])
        ->first()->licence_number; 
        $localLicence['licence_number'] = $result;
      }
        $localLicence['licence_issued'] = $localLicence->licence_issued();
    }
    }
    return response()->json($localLicence);
  }
}
