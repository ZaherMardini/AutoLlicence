<?php

namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Enums\ApplicationTypes;
use App\Global\BaseQuery;
use App\Global\Methods;
use App\Http\Requests\StoreLocalLicenceRequest;
use App\Models\Application;
use App\Models\ApplicationType;
use App\Models\LocalLicence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Collection\Collection;

class LocalLicenceService{
  public static function baseQuery()
  {
    return  LocalLicence::
    join('applications', 'applications.id', '=', 'local_licences.application_id')
    ->join('licence_classes', 'licence_classes.id', '=', 'local_licences.licence_class_id')
    ->join('people', 'people.id', '=', 'applications.person_id')
    ->select(
      'local_licences.id as licence_id',
      'people.id as person_id',
      'people.name',
      'people.national_no',
      'applications.id as application_id',
      'applications.status as status',
      'licence_classes.id as licence_class_id',
      'licence_classes.title as licence_class',
      'local_licences.created_at'
    );
  }
  public function addOptions(Collection $items){
    foreach ($items as $item) {
      $item['options'] = '<i class="fa-solid fa-ellipsis-vertical text-white text-xl"></i>';
    }
  }
  public function index(){
    $items = self::baseQuery()->get();
    $columns = LocalLicence::$columns;
    $searchBy = LocalLicence::searchBy();
    $searchRoutes = LocalLicence::$searchRoutes;
    foreach ($items as $item) {
      // $item['passedTests'] = $item->passedTests(); //bug: items are just collection, not collection of Models
      $item['passedTests'] = BaseQuery::passedTests($item['licence_id']);
    }
      // dd($items->toArray());
    return view('localLicence.index', compact('items', 'columns', 'searchBy', 'searchRoutes'));
  }
  public function store(StoreLocalLicenceRequest $request){
    DB::transaction(function () use($request) {
      $type = ApplicationType::findOrFail(ApplicationTypes::NewLocalLicence->value);
      $info = $request->validated();
      $applicationProps = [
        'application_type_id' => $type['id'],
        'created_by_user' => Auth::id(),
        'person_id' => $info['person_id'],
        'fees' => $type['fees'],
        'status' => ApplicationStatus::New->value,
      ];
      $application = Application::create($applicationProps);
      $info['application_id'] = $application['id'];
      LocalLicence::create($info);
      });
      return redirect()->route('LocalLicence.index');
  }
  public static function filter(Request $request){
    return Methods::filter(self::baseQuery(), $request, LocalLicence::searchBy(), LocalLicence::numericKeys());
  }
}