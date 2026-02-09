<?php

namespace App\Http\Controllers;

use App\Global\Methods;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
  public function index(){
    $results = DB::select(
    "
      SELECT 
      u.id 'user_id',u.name 'username', u.email 'email',
      u.isActive, p.id 'person_id',p.name 'name'
      FROM users u JOIN people p ON p.id = u.id;
    ");
    $searchRoutes = ['filter' => 'user.filter', 'find' => 'user.find'];
    $results = Methods::collectCustomQueryResults(User::$columns, $results);
    return view('users.index',[
    'users' => $results,
    'columns' => User::$columns,
    'searchBy' => User::$columns,
    'searchRoutes' => $searchRoutes]
    );
  }
}
