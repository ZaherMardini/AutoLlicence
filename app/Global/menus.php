<?php
namespace App\Global;

use App\Models\Country;

class Menus{
  public static $applications = [
    'title' => 'Applications',
    'items' => [
      'Application Types' => 'applicationTypes.index',
    ]
  ];
  public static $people = [
    'title' => 'people',
    'items' => [
      'People info'      => 'person.index',
      'New person'       => 'person.create',
      'Show Person info' => 'person.show',
      'Edit Person info' => 'person.edit',
    ]
  ];
  public static $users = [
    'title' => 'Users',
    'items' => [
      'Users info'     => 'user.index',
      'New user'       => 'user.create',
      'Edit user info' => 'profile.edit',
    ]
  ];
  public static function countries(){
    return Country::get();
  }
}