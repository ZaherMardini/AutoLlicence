<?php 
namespace App\Enums;

enum ApplicationTypes :int{
  case NewLocalLicence = 1;
  case RenewLicence = 2;
  case LostReplacement = 3;
  case DamagedReplacement = 4;
}