<?php

namespace Database\Seeders;

use App\Models\Fine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $records = [
        ['action' => 'Release detained licence', 'ammount' => 10]
      ];
      foreach ($records as $record) {
        Fine::updateOrCreate($record);
      }
    }
}
