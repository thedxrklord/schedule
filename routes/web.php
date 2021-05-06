<?php

use Illuminate\Support\Facades\Route;


\Artisan::call('config:clear');
\Artisan::call('view:clear');
\Artisan::call('route:clear');


Route::get('/dev/migrate', function() {
    \Artisan::call('migrate');
    echo 'migrated';
});

Route::get('/dev/migrate-fresh', function() {
    \Artisan::call('migrate:fresh');
    echo 'fresh migrated';
});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::any('/university/create', [\App\Http\Controllers\UniversityController::class, 'createUniversity'])->name('university.create');
Route::get('/universities', [\App\Http\Controllers\UniversityController::class, 'universities'])->name('university.list');
Route::any('/university/{id}/shared', [\App\Http\Controllers\UniversityController::class, 'shared'])->name('shared');
Route::get('/university/{id}/shared/{email}/remove', [\App\Http\Controllers\UniversityController::class, 'removeShared'])->name('shared.remove');
