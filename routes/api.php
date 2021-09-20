<?php

use App\Http\Controllers\Certificado\CertificadoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserInformationController;
use App\Http\Controllers\ClicksInformationController;

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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/set-points', 'Controller@setPoints')->name('api.set.points');
Route::get('/get-points-of-user/{email}', 'GetController@getPointsOfUser');
*/


/***  Register and login user  ***/
Route::post('/register-or-login', [UserInformationController::class, 'registerOrLoginUser']);


/***  get data users  ***/
Route::get('/user-register', [UserInformationController::class, 'getUserRegister']);
Route::get('/user-login', [UserInformationController::class, 'getUserLogin']);


/***  Register data scenes and events click  ***/
Route::post('/register-scene', [ClicksInformationController::class, 'registerSceneVisit']);
Route::post('/register-clicks', [ClicksInformationController::class, 'registerEventclick']);
Route::post('/register-model', [ClicksInformationController::class, 'registerWallModel']);

Route::get('/get-list-scenes', [ClicksInformationController::class, 'getScenesVisit']);
Route::get('/get-event-clicks', [ClicksInformationController::class, 'getEventClicks']);
Route::get('/get-points', [ClicksInformationController::class, 'getPoints']);


Route::get('/register-var', [ClicksInformationController::class, 'registerVariable']);
Route::get('/update-var', [ClicksInformationController::class, 'updateVariable']);
Route::get('/get-var', [ClicksInformationController::class, 'getVariable']);

Route::post('/check_certificate', [CertificadoController::class, 'checkCertificado']);
