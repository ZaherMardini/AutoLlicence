<?php

namespace Database\Seeders;

use App\Models\ApplicationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $licences = 
      [
        ['title' => 'New local licence', 'fees' => 15],
        ['title' => 'New international licence', 'fees' => 20],
        ['title' => 'Renew licence', 'fees' => 5],
        ['title' => 'Replacement for lost licence', 'fees' => 10],
        ['title' => 'Replacement for damaged licence', 'fees' => 10],
        ['title' => 'Release detained licence', 'fees' => 25],
      ];

      foreach ($licences as $licence) {
        ApplicationType::updateOrCreate($licence);
      }
    }
}
