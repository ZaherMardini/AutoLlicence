<?php

namespace App\Http\Controllers;

use App\Models\ApplicationType;
use App\Models\fs;
use Illuminate\Http\Request;

class ApplicationTypesController extends Controller
{
    public function index()
    {
      $columns= ApplicationType::$columns;
      $filter="true";
      $items= ApplicationType::get(); 
      $enableSearch = false;
      return view('applicationTypes.index', compact('columns', 'filter', 'items', 'enableSearch'));
    }

    public function store(Request $request)
    {
        //
    }

    public function edit()
    {
        //
    }

    public function update(Request $request)
    {
        //
    }
}
