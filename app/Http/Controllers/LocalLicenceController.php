<?php

namespace App\Http\Controllers;

use App\Enums\CardMode;
use App\Global\Methods;
use App\Http\Requests\StoreLocalLicenceRequest;
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
    return $this->service->filter($request);
  }
}
