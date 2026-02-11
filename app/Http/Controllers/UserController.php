<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
  protected $service;
  public function __construct(UserService $service)
  {
     $this->service = $service;
  }
  public function index(){
    return $this->service->index();
  }
  public function create(){
    return view('users.create');
  }

  public function store(StoreUserRequest $request)
    {
      $userProps = $request->validated();        
      User::create($userProps);
      return redirect(route('user.index', absolute: false));
    }

  public function filter(Request $request){
    return $this->service->filter($request);
  }
  public function findFirst(Request $request){
    $user = User::where($request['prop'],'like', '%' . $request['query'] . '%')->first();
    return response()->json($user);
  }
}
