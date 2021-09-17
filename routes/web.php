<?php

use Illuminate\Support\Facades\Route;

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

// ejecutar lectura en google sheet 
/* Route::get('/', function (\App\Services\GoogleSheet $goolgeSheet) {
    $goolgeSheet->readGoogleSheet();
    return view('welcome');
});  */
// insertar datos en google sheet 
/* Route::get('/', function (\App\Services\GoogleSheet $goolgeSheet) {

    $values = [
        [5, 'Jhon lj', 'otro', '2020-08-24', 4],
        [6, 'Jhon mas', 'otro mas', '2020-08-14', 5]
    ];

    $saveData = $goolgeSheet->saveDataToSheet($values);
    dump($saveData);

    dd($goolgeSheet->readGoogleSheet());
    return view('welcome');
});  */

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('certificado', [App\Http\Controllers\Certificado\CertificadoController::class, 'index']);
Route::get('download_certificado', [App\Http\Controllers\Certificado\CertificadoController::class, 'download']);
