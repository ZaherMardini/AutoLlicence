<?php

namespace Database\Seeders;

use App\Models\LicenceClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class licenceClassesSeeder extends Seeder
{
  public function run(): void
  {
      $licenceClasses = [
        [
        'title' => 'Regular driving licence',
        'description' => 'motorcycles Driving licence',
        'fees' => 20, 
        'minimum_allowed_age' => 18,
        'valid_years' => 10
        ],
        [
        'title' => 'Small motorcycles',
        'description' => 'motorcycles Driving licence',
        'fees' => 15,
        'minimum_allowed_age' => 18,
        'valid_years' => 5
        ],
        [
        'title' => 'Large motorcycles',
        'description' => 'Large motorcycles Driving licence',
        'fees' => 30,
        'minimum_allowed_age' => 21,
        'valid_years' => 10
        ],
      ];
      foreach ($licenceClasses as $class) {
        LicenceClass::updateOrCreate($class);
      }
    }
}
