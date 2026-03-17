<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    { 
      $this->call(TestTypesSeeder::class);        
      $this->call(ApplicationtypesSeeder::class);
      $this->call(licenceClassesSeeder::class);
      $this->call(FineSeeder::class);

      Country::factory(5)->create();
      // User::factory(3)
      // ->state(new Sequence(['isActive' => 1], ['isActive' => 0]))
      // ->create();
      User::create([
      'name' => 'legend',
      'email' => 'test@example.com',
      'person_id' => 1,
      'isActive' => 1,
      ]);

      $this->call(PersonSeeder::class);
    }
}
