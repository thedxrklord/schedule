<?php

use Illuminate\Support\Facades\Route;


\Artisan::call('config:clear');
\Artisan::call('view:clear');
\Artisan::call('route:clear');

Route::get('/', function () {
    return view('welcome');
});

if (\App\Models\Setting::where('key', '=', 'register')->first()->value == true) {
    Auth::routes();
} else {
    Auth::routes(['register' => false]);
}

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::any('/university/create', [\App\Http\Controllers\UniversityController::class, 'createUniversity'])->name('university.create');
Route::get('/universities', [\App\Http\Controllers\UniversityController::class, 'universities'])->name('university.list');
Route::any('/university/{id}/edit', [\App\Http\Controllers\UniversityController::class, 'editUniversity'])->name('university.edit');
Route::any('/university/{id}/shared', [\App\Http\Controllers\UniversityController::class, 'shared'])->name('shared');
Route::get('/university/{id}/shared/{email}/remove', [\App\Http\Controllers\UniversityController::class, 'removeShared'])->name('shared.remove');
Route::get('/close-register', [\App\Http\Controllers\HomeController::class, 'closeRegister'])->name('register.close');
Route::get('/open-register', [\App\Http\Controllers\HomeController::class, 'openRegister'])->name('register.open');
