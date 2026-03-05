<?php

namespace App\View\Components;

use App\Models\Licence;
use App\Models\LocalLicence;
use Illuminate\View\Component;

class DrivingLicenceCard extends Component
{
    public $licence;
    public function __construct(Licence $licence) {
      $this->licence = $licence;
    }

    public function render()
    {
      return view('components.driving-licence-card');
    }
}