<?php

namespace App\Http\Controllers;

use App\Global\Methods;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;


class UserController extends Controller
{
  public function index(){
    $results = User::join('people', 'people.id', '=', 'users.person_id')
    ->select(
        'users.id',
        'users.name',
        'users.email',
        'users.person_id',
        'users.isActive',
        'people.name as person_name'
    )
    ->orderBy('users.created_at', 'desc')
    ->get();
    return view('users.index',[
    'users' => $results,
    'columns' => User::$columns,
    'searchBy' => User::searchBy(),
    'searchRoutes' => User::$searchRoutes
    ]
    );
  }
  public function create(){
    return view('users.create');
  }

  public function store(Request $request)
    {
      $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'person_id' => ['required', 'exists:people,id', 'unique:users,person_id'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'isActive' => ['required']
        ]);
        
        // dd($request);
        $user = User::create([
          'isActive' => $request->isActive,
          'person_id' => $request->person_id,
          'name' => $request->name,
          'email' => $request->email,
          'password' => Hash::make($request->password),
        ]);


        // Auth::login($user);

        return redirect(route('user.index', absolute: false));
    }

  public function filter(Request $request){
    $prop = $request['prop'];
    $value = $request['value'];
    $searchBy = User::searchBy();
    $numeric = collect($searchBy)->only(['User ID', 'Person ID'])->toArray();
    if(!in_array($prop, $searchBy, true)){
        return response()->json(abort('401','Invalid filter'));
      };
      $results = User::join('people', 'people.id', '=', 'users.person_id')
      ->select(
          'users.id',
          'users.name',
          'users.email',
          'users.person_id',
          'users.isActive',
          'people.name as person_name'
      );
    if(in_array($prop, $numeric, true) && $value != ''){
        $results->where($prop, $value);
    }
    else{
        $results->where($prop, 'like', "%{$value}%");
    };
    // exists in search by
    // numeric or text
    return response()->json($results->get());
  }
  public function findFirst(Request $request){
    $user = User::where($request['prop'],'like', '%' . $request['query'] . '%')->first();
    return response()->json($user);
  }
}
