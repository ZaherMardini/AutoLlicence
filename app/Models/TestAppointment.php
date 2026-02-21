<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestAppointment extends Model
{
  /** @use HasFactory<\Database\Factories\TestAppointmentFactory> */
  use HasFactory;
  public function testType(){
    return $this->belongsTo(TestType::class);
  }
  public static $columns = [
    'ID' => 'id',
    'Test Type' => 'test_type',
    'Licence class' => 'class',
    'Appointment Date' => 'appointment_date',
    'Paid fees' => 'paid_fees'
  ];
  // public function testType(){
  //   return $this->belongsTo(TestType::class);
  // }
}
