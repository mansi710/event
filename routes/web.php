<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalanderEventController;
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

Route::get('/fullcalender',[CalanderEventController::class,"index"])->name('fullcalender');
Route::get('/fetchEvents',[CalanderEventController::class,"fetchEvents"])->name('event.show');
Route::get("/editEvent/{id}",[CalanderEventController::class,'editEvent'])->name('editEvent');
Route::delete('/destroyEvent/{id}',[CalanderEventController::class,'destroyEvent'])->name('event.destroy');;
Route::patch("/updateEvent/{id}",[CalanderEventController::class,'updateEvent'])->name('updateEvent');