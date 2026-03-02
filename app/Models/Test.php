<?php

namespace App\Models;

use App\Global\BaseQuery;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    /** @use HasFactory<\Database\Factories\TestFactory> */
    use HasFactory;
    public static function testIsPassed(TestAppointment $appointment){
      return BaseQuery::simple_test_Passed($appointment);
    }
}
