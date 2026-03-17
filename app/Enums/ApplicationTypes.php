<?php 
namespace App\Enums;

enum ApplicationTypes :int{
  case NewLocalLicence = 1;
  case NewInternationalLicence = 2;
  case RenewLicence = 3;
  case LostReplacement = 4;
  case DamagedReplacement = 5;
  case ReleaseDetained = 6;
}