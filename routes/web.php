<?php

use Illuminate\Support\Facades\Route;
use App\Models\Pro;
use App\Http\Controllers\ProController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\proTagController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/info', function () {
    return view('info');
});
Route::get('/start', function () {
    return view('start');
});

Route::get('/test/{name}',[ProController::class,'index']);
Route::resource("pro",ProController::class);
Route::resource("tag",TagController::class);
Route::resource("user",UserController::class);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/pro/tag/{tag_id}', [App\Http\Controllers\proTagController::class, 'getFilteredProj'])->name('pro_tag');

Route::get('/pro/{pro_id}/tag/{tag_id}', [App\Http\Controllers\proTagController::class, 'attachTag'])->name('pro_tag');

Route::get('/pro/{pro_id}/tag/{tag_id}', [App\Http\Controllers\proTagController::class, 'detachTag'])->name('pro_tag');
Route::post('/pro', [ProController::class,'store']);
Route::post('/tag', [TagController::class,'store']);
