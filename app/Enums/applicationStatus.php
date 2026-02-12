<?php
namespace App\Enums;

enum ApplicationStatus:string
{
  case New   = 'new';
  case Pending   = 'pending';
  case Cancelled = 'cancelled';
  case Completed = 'completed';
}