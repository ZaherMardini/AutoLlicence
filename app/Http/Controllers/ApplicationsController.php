<?php

namespace App\Http\Controllers;

use App\Global\BaseApplicationFee;
use App\Global\BaseQuery;
use App\Http\Requests\StoreApplicationRequest;
use App\Models\Application;
use App\Models\ApplicationType;

class ApplicationsController extends Controller
{
  public function index(){
    $items = BaseQuery::applications()->get();
    $columns = Application::$columns;
    foreach($items as $item){
      $item['fees'] += BaseApplicationFee::$fee;
    }
    return view('Applications.index', compact('columns', 'items'));
  }
  public function store(StoreApplicationRequest $request){
    $info = $request->validated();
    Application::create($info);
    return redirect()->route('applications.index');
  }
}
