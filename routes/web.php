<?php

use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/maps', [App\Http\Controllers\PageController::class, 'index'])->name('maps.index');

Route::get('/maps/{id}', [App\Http\Controllers\PageController::class, 'show'])->name('maps.show');

Route::get('/admin/maps/{id}/downloadJson', [MapController::class, 'downloadJson'])->name('admin.maps.downloadJson');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/category', [App\Http\Controllers\HomeController::class, 'category'])->name('category');

Route::get('/about', [App\Http\Controllers\HomeController::class, 'about'])->name('about');

Route::get('/latest_news', [App\Http\Controllers\HomeController::class, 'latest_news'])->name('latest_news');

Route::get('/contact', [App\Http\Controllers\HomeController::class, 'contact'])->name('contact');

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::resource('/maps', MapController::class);
});
