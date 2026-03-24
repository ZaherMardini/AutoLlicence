<?php

use App\Enums\permissions;
use App\Http\Controllers\ApplicationsController;
use App\Http\Controllers\ApplicationTypesController;
use App\Http\Controllers\LicenceController;
use App\Http\Controllers\LocalLicenceController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestAppointmentController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/test', function () {
  $user = Auth::user();
  $countries = Country::get();
  return view('test', ['countries' => $countries]);
})->middleware(['auth', 'verified'])->name('test');

Route::get('/dashboard', function () {
  return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware(['auth', 'can:hasAccessTo,' . permissions::View->value])->group(function () {
//   Route::get('/people', [PersonController::class, 'index'])->name('person.index');
//   // dd(Auth::user());
// });

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

  // Person
  Route::get('/people', [PersonController::class, 'index'])->name('person.index');
  Route::get('/people/create', [PersonController::class, 'create'])->name('person.create');
  Route::get('/people/edit', [PersonController::class, 'edit'])->name('person.edit');
  Route::post('/people/store', [PersonController::class, 'store'])->name('person.store');
  Route::post('/people/update/{person}', [PersonController::class, 'update'])->name('person.update');
  Route::get('/people/show', [PersonController::class, 'show'])->name('person.show');
  // Person
  // User
  Route::get('/users',                [UserController::class, 'index'])->name('user.index');
  Route::get('/users/edit',           [UserController::class, 'edit'])->name('user.edit');
  Route::post('/users/update/{user}', [UserController::class, 'update'])->name('user.update');
  Route::get('/users/show',           [UserController::class, 'show'])->name('user.show');
  // User

  // Applications Types & LDL
  Route::get('/applicationTypes',    [ApplicationTypesController::class, 'index'])->name('applicationTypes.index');
  Route::get('/LocalLicences',       [LocalLicenceController::class, 'index'])->name('localLicence.index');
  Route::get('/LocalLicences/create',[LocalLicenceController::class, 'create'])->name('localLicence.create');
  Route::post('/LocalLicences/store',[LocalLicenceController::class, 'store'])->name('localLicence.store');
  // Application Types & LDL

  // Licence operations
  Route::get('/licence/find',                                  [LicenceController::class, 'find'])                    ->name('licence.find');
  Route::get('/licence/filter',                                [LicenceController::class, 'filter'])                  ->name('licence.filter');
  Route::get('/licence/{licence:licence_number}',              [LicenceController::class, 'show'])                    ->where('licence', '^LIC-\d{4}-\d{5}$')->name('licence.show');
  Route::get('/licence/{licence}/operations',                  [LicenceController::class, 'operations'])              ->name('licence.operations');
  Route::post('/licence/{localLicence}/create',                [LicenceController::class, 'store'])                   ->name('licence.store');
  Route::patch('/licence/{licence}/detainRelease',             [LicenceController::class, 'detainRelease'])           ->name('licence.detainRelease');
  Route::patch('/licence/{licence}/renew',                     [LicenceController::class, 'renew'])                   ->name('licence.renew');
  Route::post('/licence/{old_licence}/replace',                     [LicenceController::class, 'replace'])                   ->name('licence.replace');
  Route::post('/licenceOperationApplication/{licence}/{applicationType}',    [LicenceController::class, 'createOperationApplication'])    ->name('licence.createOperationApplication');
  // Licence operations

  // Applications
  Route::get('/applications', [ApplicationsController::class, 'index'])        ->name('applications.index');
  Route::get('/applications/create', [ApplicationsController::class, 'create'])->name('applications.create');
  Route::post('/applications', [ApplicationsController::class, 'store'])       ->name('applications.store');
  // Applications
  
  // Test appointments
  Route::get('/appointments',                                  [TestAppointmentController::class, 'index'] )->name('appointments.index');
  Route::get('/appointments/{localLicence}/{testType}/create', [TestAppointmentController::class, 'create'])->name('appointments.create');
  Route::post('/appointments/{licence_id}',                    [TestAppointmentController::class, 'store'] )->name('appointments.store');
  Route::get('/appointments/find',                             [TestAppointmentController::class, 'find']  )->name('appointments.find');
  Route::get('/appointments/{licence_id}/edit',                [TestAppointmentController::class, 'edit']  )->name('appointments.edit');
  Route::post('/appointments/{licence_id}/update',             [TestAppointmentController::class, 'update'])->name('appointments.update');
  Route::post('/appointments/{licence_id}/cancel',             [TestAppointmentController::class, 'cancel'])->name('appointments.cancel');
  // Test appointments
  
  // Test
  Route::get('/tests/{localLicence}/{testType}/create', [TestController::class, 'create'])->name('tests.create');
  Route::post('/tests/{localLicence}/{testType}/create', [TestController::class, 'store'])->name('tests.store');
  // Test
  });
  Route::get('/users/filter',         [UserController::class, 'filter'])->name('user.filter');
  Route::get('/users/find',           [UserController::class, 'find'])->name('user.find');
  Route::get('/people/filter',        [PersonController::class, 'filter'])->name('person.filter');
  Route::get('/people/find',          [PersonController::class, 'find'])->name('person.find');
  Route::get('/localLicences/filter', [LocalLicenceController::class, 'filter'])->name('localLicence.filter');
  Route::get('/localLicences/find',   [LocalLicenceController::class, 'find'])->name('localLicence.find');
  Route::get('/localLicences/show',   [LocalLicenceController::class, 'show'])->name('localLicence.show');

  Route::post('/users/store',         [UserController::class, 'store'])->name('user.store');
  Route::get('/users/create',         [UserController::class, 'create'])->name('user.create');
  
  require __DIR__.'/auth.php';
