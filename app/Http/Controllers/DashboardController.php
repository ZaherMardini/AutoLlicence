<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStatus;
use App\Enums\ApplicationTypes;
use App\Enums\LicenceStatus;
use App\Models\Application;
use App\Models\Licence;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  public function index(){
    $dashboardProps = [
     'totalLicences' => Licence::where('status', LicenceStatus::new->value)->count(),
     'pendingApplications' => Application::where('status', ApplicationStatus::New->value)
                                       ->orWhere('status', ApplicationStatus::Pending->value)->count(), 
     'recentApplications' => Application::latest()->with('person:id,name')->limit(3)->get(), 
     'expiredLicences' => Licence::where('status', LicenceStatus::expired->value)->count(), 
     'renewalsThisMonth' => Application::where('application_type_id', ApplicationTypes::RenewLicence->value)->count(),
    ];
    // dd($dashboardProps);
    return view('dashboard', compact('dashboardProps'));
  }
}
