<?php
namespace App\Global;

use App\Models\Country;

class Menus{
  public static $applications = [
    'title' => 'Applications',
    'options' => [
      'Application Types' => 'applicationTypes.index',
      'Applications' => 'applications.index',
      'New local driving licence' => 'LocalLicence.create',
      'Show licence info' => 'LocalLicence.show',
      'Local driving licences' => 'LocalLicence.index',
      'Replacement for damaged licence' => 'LocalLicence.create',
      'Replacement for lost licence' => 'LocalLicence.create',
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
  public static $tests = [
    'title' => 'Schedule tests',
    'options' => [
      'Vision test'  => 'appointments.create',
      // 'Written test' => 'appointments.create',
      // 'Street test'  => 'appointments.create',
    ]
  ];
}