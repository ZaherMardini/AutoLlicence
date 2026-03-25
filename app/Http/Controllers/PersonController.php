<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonRequest;
use App\Enums\CardMode;
use App\Enums\permissions;
use App\Global\Current;
use App\Http\Requests\UpdatePersonRequest;
use App\Models\Person;
use App\Services\PersonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
    $searchBy = [];
    $searchRoutes = [];
    $mode = CardMode::new->value;
    return view('people.person', compact('mode', 'searchBy', 'searchRoutes'));
  }
  public function edit(){
    $searchBy = Person::searchBy(); 
    $searchRoutes = Person::$searchRoutes;
    $mode = CardMode::edit->value;
    return view('people.person', compact('mode', 'searchBy', 'searchRoutes'));
  }
public function store(StorePersonRequest $request){
  $this->service->store($request);
  return redirect()->route('person.index'); 
}
  public function update(UpdatePersonRequest $request, Person $person){
    $this->service->update($request, $person);
    return redirect()->route('person.index');
  }
  public function filter(Request $request){ 
    return $this->service->filter($request);
  }
  public function find(Request $request){
    $person = Person::where($request['searchKey'],'like', '%' . $request['value'] . '%')->first();
    Current::$person = $person;
    return response()->json($person);
  }
}
