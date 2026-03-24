<?php

namespace App\Enums;

enum permissions : int {
  case Sudo = 15;
  case View = 1;
  case Create = 2;
  case Edit = 4;
  case Delete = 8;
}