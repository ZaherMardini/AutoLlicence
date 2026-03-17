<?php

namespace App\Enums;

enum LicenceStatus: string
{
  case new = 'Active';
  case deactivated = 'Deactivated';
  case detained = 'Detained';
  case expired = 'Expired';
}