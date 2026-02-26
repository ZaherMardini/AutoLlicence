<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeTestRequest;
use App\Models\LocalLicence;
use App\Models\Test;
use App\Models\TestAppointment;
use App\Models\TestType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
  public function create(LocalLicence $localLicence, TestType $testType){
    $appointment = TestAppointment
    ::where('local_licence_id', $localLicence['id'])
    ->where('test_type_id', $testType['id'])
    ->with('test_type')->first();
    dump($appointment->toArray());
    $appointment['trials'] = $appointment->testtrials();
    return view('tests.create', compact('appointment'));
  }
  public function store(storeTestRequest $request){
    dump($request->toArray());
    $info = $request->validated(); 
    dd($info->toArray());
    DB::transaction(function () use($info){
      Test::create($info);
      TestAppointment::where('test_appointment_id', $info['test_appointment_id'])->update(['isLocked' => 1]);
    });
    return redirect()->route('LocalLicence.index');
  }
}
