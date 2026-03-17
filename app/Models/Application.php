<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
  use HasFactory;

  public static $columns = [
      'ID' => 'id',
      'Person ID' => 'person_id',
      'Person name' => 'person_name',
      'Application Type (Service)' => 'service',
      'Status' => 'status',
      'Fees' => 'fees'
  ];
  public static function UniqueApplication(int $person_id, int $application_type_id){
    $count = Application::where('person_id', $person_id)
            ->where('application_type_id', $application_type_id)
            ->where('status', ApplicationStatus::New->value)->count();
    return $count === 0;
  }
  public function person(){
    return $this->belongsTo(Person::class);
  }
}
