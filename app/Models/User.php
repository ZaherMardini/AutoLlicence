<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use HasFactory, Notifiable;
  protected $guarded = [];
  // public static $cccolumns =
  // [
  //   'User ID'  ,
  //   'User name',
  //   'Email'    ,
  //   'Person ID',
  //   'Is Active',  
  //   'Person name',
  // ];


  public static $columns =
  [
    'User ID'     => 'id',
    'User name'   => 'name',
    'Email'       => 'email',
    'Person ID'   => 'person_id',
    'Is Active'   => 'isActive' ,
    'Person name' => 'person_name',
  ];
  public static function numericKeys(){
    return collect(self::searchBy())->only(['User ID', 'Person ID'])->toArray();
  }
  public static $searchRoutes = ['filter' => 'user.filter', 'find' => 'user.find'];
  
  public static function searchBy(){
    return [
    'User ID'     => 'users.id',
    'User name'   => 'users.name',
    'Email'       => 'users.email',
    'Person ID'   => 'users.person_id',
    'Person name' => 'people.name',
    ];
  }
  public function UpdatePermission(){
    $this->update(['permissions' => $this['permissions']]);
  }
  public function addPermission(int $permission){
    $this['permissions'] = $this['permissions'] | $permission;
  }
  public function person(){
    return $this->belongsTo(Person::class);
  }
  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
    ];
  }
}
