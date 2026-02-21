<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonRequest;
use App\Enums\CardMode;
use App\Global\Current;
use App\Http\Requests\UpdatePersonRequest;
use App\Models\Person;
use App\Services\PersonService;
use Illuminate\Http\Request;

class PersonController extends Controller
{
  protected $service;
  public function __construct(PersonService $service)
  {
    $this->service = $service;
  }
  public function index(){
    return $this->service->index();
  }
  public function show(){
    $searchBy = Person::searchBy();
    $searchRoutes = Person::$searchRoutes;
    $mode = CardMode::read->value;
    return view('people.person', compact('searchBy','searchRoutes','mode'));
  }
  public function create(){
    return view('people.person', ['mode' => CardMode::new->value]);
  }
  public function edit(){
    return view('people.person', ['mode' => CardMode::edit->value]);
  }
public function store(StorePersonRequest $request){
    return $this->service->store($request);
}
  public function update(UpdatePersonRequest $request, Person $person){
    return $this->service->update($request, $person);
  }
  public function filter(Request $request){ 
    return $this->service->filter($request);
  }
  public function findFirst(Request $request){
    $person = Person::where($request['searchKey'],'like', '%' . $request['value'] . '%')->first();
    Current::$person = $person;
    return response()->json($person);
  }
}
