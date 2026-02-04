<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonRequest;
use App\Models\Country;
use App\Models\Person;
use App\Global\Current;
use Illuminate\Http\Request;

class PersonController extends Controller
{
  public function index(){
    $people = Person::latest();
    $people = $people->get();
    $columns = $people[0]->getColumns();
    return view('people.index', ['people' => $people, 'columns' => $columns]);
    // $people = Person::latest()->simplePaginate(10);
  }
  public function show(Person $person){
    $countries = Country::get();
    return view('people.create', ['person' => $person, 'mode' => 'read', 'countries' => $countries]);
  }
  public function create(){
    $countries = Country::get();
    $person = null; // the model to view its id after inserting
    return view('people.create', ['person' => $person,'countries' => $countries, 'mode' => 'new']);
  }
  public function edit(){
    $countries = Country::get();
    return view('people.edit', ['countries' => $countries, 'mode' => 'edit']);
  }
  public function store(StorePersonRequest $request){
    $info = $request->validated();
    $path = "";
    if($request->file('file')){
      $path = $request->file('file')->store('images/people','public');
      unset($info['file']);
      $info['image_path'] = $path;
      }
      else{
        $info['gender'] === 'male' ? 
        $info['image_path'] = '/images/defaults/male.png'
        :$info['image_path'] = '/images/defaults/female.png';
      }

    $person = Person::create($info);
    $coutnries = Country::get();
    return view('people.create', ['person' => $person, 'countries' => $coutnries, 'mode' => 'edit']);
  }
  public function update(StorePersonRequest $request){
  }
  public function search(Request $request){
    $people = Person::where($request['prop'],'like', '%' . $request['search'] . '%')->get();
    return response()->json($people);
  }
  public function findFirst(Request $request){
    $person = Person::where($request['prop'],'like', '%' . $request['search'] . '%')->first();
    return response()->json($person);
  }
}
