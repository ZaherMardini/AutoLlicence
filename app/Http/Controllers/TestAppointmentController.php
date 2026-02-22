<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationTypes;
use App\Enums\CardMode;
use App\Enums\TestTypes;
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
  public function create(LocalLicence $localLicence, TestType $testType){
    $searchBy = Person::searchBy();
    $searchRoutes = Person::$searchRoutes;
    $person = Person::findOrFail($localLicence['person_id']);
    $mode = CardMode::locked->value;
    $appointments = BaseQuery::testAppointments()->where('people.id', $person['id'])->get();
    $localLicence->load('licenceClass');
    $columns = TestAppointment::$columns;
    return view('appointments.create', 
    compact(
      'testType',
      'appointments',
      'columns',
    'mode',
     'searchBy',
      'searchRoutes',
      'localLicence',
      'person',
      ));  
  }
  public function store(StoreTestAppointment $request){
    $info = $request->validated();
    $info['paid_fees'] = TestAppointment::paidFees(TestTypes::VisionTest->value, ApplicationTypes::NewLocalLicence->value);
    $result = TestAppointment::create($info);
    return redirect()->route('appointments.create', ['localLicence' => $result['local_licence_id'], 'testType' => $info['test_type_id']]);
  }
  public function edit(){
    dd('not implemented');
  }
  public function update(){
    dd('not implemented');
  }
}
