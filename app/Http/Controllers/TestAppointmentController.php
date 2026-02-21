<?php

namespace App\Http\Controllers;

use App\Enums\CardMode;
use App\Global\BaseQuery;
use App\Http\Requests\StoreTestAppointment;
use App\Models\ApplicationType;
use App\Models\LocalLicence;
use App\Models\Person;
use App\Models\TestAppointment;
use App\Models\TestType;

class TestAppointmentController extends Controller
{
  public function index(){
    $appointments = BaseQuery::testAppointments()->get();
    $columns = TestAppointment::$columns;
    return view('appointments.index', compact('appointments', 'columns'));
  }
  public function create(LocalLicence $localLicence){
    $searchBy = Person::searchBy();
    $searchRoutes = Person::$searchRoutes;
    $person = Person::findOrFail($localLicence['person_id']);
    $mode = CardMode::locked->value;
    $localLicence->load('licenceClass');
    return view('appointments.create', 
    compact(
    'mode',
     'searchBy',
      'searchRoutes',
      'localLicence',
      'person',
      ));  
  }
  public function store(StoreTestAppointment $request){
    // dd($request->toArray());
    $info = $request->validated();
    $info['test_type_id'] = 1;
    $info['paid_fees'] = TestType::find(1)['fees'] + ApplicationType::find(1)['fees'];
    // dd($info);
    TestAppointment::create($info);
    return redirect()->route('appointments.index');
  }
  public function edit(){
    dd('not implemented');
  }
  public function update(){
    dd('not implemented');
  }
}
