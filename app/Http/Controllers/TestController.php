<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeTestRequest;
use App\Models\LocalLicence;
use App\Models\Test;
use App\Models\TestAppointment;
use App\Models\TestType;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
  public function create(LocalLicence $localLicence, TestType $testType){
    // dd([$localLicence['id'], $testType['id']]);
    $appointment = TestAppointment
    ::where('local_licence_id', $localLicence['id'])
    ->where('test_type_id', $testType['id'])
    ->where('isLocked', 0)
    ->with('test_type')
    ->first();
    if(!$appointment){
      abort(404);
    }
    $appointment['trials'] = $appointment->testtrials();
    return view('tests.create', compact('appointment'));
  }
  public function store(storeTestRequest $request){
    $info = $request->validated(); 
    // dd($info);
    DB::transaction(function () use($info){
      Test::create($info);
      TestAppointment::where('id', $info['test_appointment_id'])->update(['isLocked' => 1]);
    });
    return redirect()->route('LocalLicence.index');
  }
}
