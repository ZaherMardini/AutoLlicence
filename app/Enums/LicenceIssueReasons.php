<?php

namespace App\Enums;

enum LicenceIssueReasons: string{
  case new = "First time Issued";
  case renewed = "Renewed Licnece";
  case damage = "Replacement for damaged";
  case lost = "Replacement for lost";
}