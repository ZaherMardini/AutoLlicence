<?php

use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProfileController;
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

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

  // Person
  Route::get('/people', [PersonController::class, 'index'])->name('person.index');
  Route::get('/people/create', [PersonController::class, 'create'])->name('person.create');
  Route::get('/people/edit', [PersonController::class, 'edit'])->name('person.edit');
  Route::post('/people/store', [PersonController::class, 'store'])->name('person.store');
  Route::post('/people/update', [PersonController::class, 'update'])->name('person.update');
  Route::get('/people/search', [PersonController::class, 'search'])->name('person.search');
  Route::get('/people/find', [PersonController::class, 'findFirst'])->name('person.find');
  Route::get('/people/show', [PersonController::class, 'show'])->name('person.show');
  // Person
});

require __DIR__.'/auth.php';
