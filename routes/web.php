<?php

use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/maps', [App\Http\Controllers\PageController::class, 'index'])->name('maps.index');

Route::get('/maps/{id}', [App\Http\Controllers\PageController::class, 'show'])->name('maps.show');

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::resource('/maps', MapController::class);
});
