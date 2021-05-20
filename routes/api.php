<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
Route::group(['prefix'=>'auth'],function()
{
	Route::post('/register',[App\Http\Controllers\MainController::class,'register']);
	Route::post('/login',[App\Http\Controllers\MainController::class,'login']);
	Route::group(['middleware'=>'auth:api'],function(){
		Route::get('/logout',[App\Http\Controllers\MainController::class,'logout']);
		Route::get('/profile',[App\Http\Controllers\MainController::class,'profile']);
	});
});
