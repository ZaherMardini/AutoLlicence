<?php

namespace App\Http\Requests;

use App\Models\Test;
use App\Models\TestAppointment;
use Illuminate\Foundation\Http\FormRequest;

class storeTestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
  public function rules(): array
  {
    return [
      'test_type_id' => ['required', 'exists:test_types,id'],
      'test_appointment_id' => ['required', 'exists:test_appointments,id'],
      'created_by_user_id' => ['required', 'exists:users,id'],
      'result' => ['required', 'boolean'],
      'notes' => ['required', 'string', 'min:3', 'max:20'], 
    ];
  }

  public function withValidator($validator){
    $validator->after(function() use($validator){
      if($validator->errors()->has('test_type_id')){
        return;
      }
      $appointmentId = $this->input('test_appointment_id');
      $appointment = TestAppointment::find($appointmentId);
      $testIsPassed = Test::testIsPassed($appointment);
      if($testIsPassed){
        $validator->errors()->add(
          'test_type_id',
          'Person already passed this test'
        );
      }
    });
  }
}
