<?php
namespace App\Rules;

use App\Models\Licence;

class LicenceOperatisonRules{
  public static function baseRules(){
    return [
      'licence_service' => ['required'],
      'licence_id' => ['required', 'exists:licences,id'],
    ];
  }
  public static function deactivatedLicenceCase($validator, string $errorPlaceHolder = 'licence_service'){
    return $validator->errors()->add($errorPlaceHolder, 'No services allowed for deactivated licences.');
  }
  public static function operationApplicationExists($request, $validator, $licence, string $errorPlaceHolder = 'licence_service'){
    $typeId = $request->input('licence_service');
    $applicationExists = Licence::applicationExists($licence, $typeId);
    if(!$applicationExists){
      $errorMessage = 'You need an application for this service.';
      if($errorMessage){
        $validator->errors()->add($errorPlaceHolder, $errorMessage);
      }
    }
  }
  public static function operationApplicationDuplicated($request, $validator, $licence){
    $typeId = $request->input('service_type');
    $applicationExists = Licence::applicationExists($licence, $typeId);
    if($applicationExists){
      $errorMessage = 'There is an application for this service.';
      if($errorMessage){
        $validator->errors()->add('licence_action', $errorMessage);
      }
    }
  }
}