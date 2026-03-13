<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStatus;
use App\Enums\ApplicationTypes;
use App\Enums\CardMode;
use App\Enums\TestTypes;
use App\Global\BaseQuery;
use App\Http\Requests\StoreTestAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Application;
use App\Models\LocalLicence;
use App\Models\Person;
use App\Models\TestAppointment;
use App\Models\TestType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TestAppointmentController extends Controller
{
  public function index(){
    $appointments = BaseQuery::testAppointments()->get();
    $columns = TestAppointment::$columns;
    $options = [];
    return view('appointments.index', compact('appointments', 'columns'));
  }
  public function create(LocalLicence $localLicence, TestType $testType){
    if(Gate::denies('check test order', [$localLicence['id'], $testType['id']])){     
      abort(403, 'Not allowed to take this test');
    }
    $searchBy = LocalLicence::searchBy();
    $searchRoutes = LocalLicence::$searchRoutes;
    $searchRoutes = TestAppointment::$searchRoutes;
    $person = Person::findOrFail($localLicence['person_id']);
    $mode = CardMode::locked->value;
    $appointments = BaseQuery::testAppointments()->where('people.id', $person['id'])->get();
    $localLicence->load('licenceClass');
    $columns = TestAppointment::$columns;
    // dump([$person->id, $testType->id]);
    $activeAppointmentExists = TestAppointment::activeAppointmentExists($person->id, $testType->id);
    $testIsPassed = TestAppointment::testIsPassed($localLicence, $testType['id']);
    return view('appointments.appointment', 
    compact(
      'testIsPassed',
      'testType',
      'activeAppointmentExists',
      'appointments',
      'columns',
      'mode',
      'searchBy',
      'searchRoutes',
      'localLicence',
      'person',
      ));  
  }
  public function store(StoreTestAppointmentRequest $request){
    $info = $request->validated();
    $appointment = DB::transaction(function() use($info) {
      $info['paid_fees'] = TestAppointment::paidFees(TestTypes::VisionTest->value, ApplicationTypes::NewLocalLicence->value);
      $appointment = TestAppointment::create($info);
      $localLicence = LocalLicence::find($appointment['local_licence_id']);
      $application = Application::where('id', $localLicence['application_id']);
      $application->update(['status' => ApplicationStatus::Pending->value]);
      return $appointment;
    });
    return redirect()->route('appointments.create', ['localLicence' => $appointment['local_licence_id'], 'testType' => $info['test_type_id']]);
  }

public function store_gpt(StoreTestAppointmentRequest $request)
{
    $appointment = DB::transaction(function () use ($request) {

        $info = $request->validated();

        $info['paid_fees'] = TestAppointment::paidFees(
            TestTypes::VisionTest->value,
            ApplicationTypes::NewLocalLicence->value
        );

        $appointment = TestAppointment::create($info);

        $localLicence = LocalLicence::find($appointment['local_licence_id']);

        Application::where('id', $localLicence['application_id'])
            ->update(['status' => ApplicationStatus::Pending->value]);

        return $appointment;
    });

    return redirect()->route('appointments.create', [
        'localLicence' => $appointment->local_licence_id,
        'testType' => $request->test_type_id
    ]);
}



  public function find(Request $request){
    $appointments = BaseQuery::testAppointments()->where('test_appointments.id', $request['value'])->get();
    foreach ($appointments as $appointment) {
      $appointment['trials'] = TestAppointment::find($appointment['id'])->testtrials();
    } 
    return response()->json($appointments);
  }
  public function edit(){
    dd('not implemented');
  }
  public function update(UpdateAppointmentRequest $request){
    $info = $request->validated();
    $activeAppointment = TestAppointment::
    where('person_id', $info['person_id'])
    ->where('test_type_id', $info['test_type_id'])
    ->where('local_licence_id', $info['local_licence_id'])
    ->where('isLocked', 0)
    ->first();
    if(!$activeAppointment){
      abort(404, 'No active appointment to update');
    }
    $activeAppointment->update(['appointment_date' => $info['appointment_date']]);
    return redirect()->route('appointments.create', ['localLicence' => $info['local_licence_id'], 'testType' => $info['test_type_id']]);
  }
  public function cancel(Request $request){
    // dd($request->toArray());
    $activeAppointment = TestAppointment::
    where('person_id', $request['person_id'])
    ->where('test_type_id', $request['test_type_id'])
    ->where('local_licence_id', $request['local_licence_id'])
    ->where('isLocked', 0)
    ->first();
    if(!$activeAppointment){
      abort(404, 'No active appointment to delete');
    }
    $activeAppointment->update(['isLocked' => 1]);
    return redirect()->route('appointments.create', ['localLicence' => $request['local_licence_id'], 'testType' => $request['test_type_id']]);
  }
}
