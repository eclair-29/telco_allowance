<?php

use App\Http\Controllers\ApproverController;
use App\Http\Controllers\PublisherController;
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

Route::get('/', function () {
    return view('auth.login');
});
Auth::routes();

Route::group(['middleware' => ['role:publisher']], function () {
    Route::get('/publisher', [PublisherController::class, 'index'])->name('publisher.index');
});

Route::group(['middleware' => ['role:approver']], function () {
    Route::get('/approver', [ApproverController::class, 'index'])->name('approver.index');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
