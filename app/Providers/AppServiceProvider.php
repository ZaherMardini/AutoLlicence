<?php

namespace App\Providers;

use App\Enums\TestTypes;
use App\Global\BaseQuery;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
      Model::unguard();
      Gate::define('check test order', function(User $user, int $localLicenceId, int $testTypeId) {
        $pass = false;  
        if($testTypeId === TestTypes::VisionTest->value){
          $pass = BaseQuery::passedTests($localLicenceId) === 0;
        }
        if($testTypeId === TestTypes::WrittenTest->value){
          $pass = BaseQuery::passedTests($localLicenceId) === 1;
        }
        if($testTypeId === TestTypes::StreetTest->value){
          $pass = BaseQuery::passedTests($localLicenceId) === 2;
        }
        return $pass;
      });

      Gate::define('hasAccessTo', function ($user, $permission) {
        dd($user);
        $result = ($user['permissions'] & $permission) === $permission;   
        return $result;
      });
    }
}
