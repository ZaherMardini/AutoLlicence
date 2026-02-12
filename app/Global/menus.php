<?php
namespace App\Global;

use App\Models\Country;

class Menus{
  public static $applications = [
    'title' => 'Applications',
    'options' => [
      'Application Types' => 'applicationTypes.index',
      'New local driving license' => 'ldl.index',
    ]
  ];
  public static $people = [
    'title' => 'people',
    'options' => [
      'People info'      => 'person.index',
      'New person'       => 'person.create',
      'Show Person info' => 'person.show',
      'Edit Person info' => 'person.edit',
    ]
  ];
  public static $users = [
    'title' => 'Users',
    'options' => [
      'Users info'     => 'user.index',
      'New user'       => 'user.create',
      'Edit user info' => 'profile.edit',
    ]
  ];
  public static function countries(){
    return Country::get();
  }
}