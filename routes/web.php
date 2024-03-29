<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Route::get('/', [LoginController::class, 'index'])->middleware('auth');
// Route::post('/login', [LoginController::class, 'index'])->middleware('auth');
Auth::routes();
Route::group(['middleware' => 'auth', 'middleware' => 'admin_middleware'], function () {

Route::get('/', [DashboardController::class,'index'])->middleware('auth');

});




