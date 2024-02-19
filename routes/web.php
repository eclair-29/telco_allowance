<?php

use App\Http\Controllers\ApproverController;
use App\Http\Controllers\AssignessController;
use App\Http\Controllers\LoansController;
use App\Http\Controllers\PlansController;
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

    Route::get('/publisher/assignees', [AssignessController::class, 'index'])->name('publisher.assignees');
    Route::post('/publisher/assignees', [AssignessController::class, 'store'])->name('publisher.assignees.store');
    Route::put('/publisher/assignees/{id}', [AssignessController::class, 'update'])->name('publisher.assignees.update');
    Route::get('/publisher/assignees/get_assignee', [AssignessController::class, 'getAssigneeInfoById']);

    Route::get('/publisher/plans', [PlansController::class, 'index'])->name('publisher.plans');
    Route::get('/publisher/plans/get_plan_fee', [PlansController::class, 'getPlanFeeById']);

    Route::get('/publisher/loans', [LoansController::class, 'index'])->name('publisher.loans');
    Route::post('/publisher/loans', [LoansController::class, 'store'])->name('publisher.loans.store');
    Route::put('/publisher/loans/{id}', [LoansController::class, 'update'])->name('publisher.loans.update');

    Route::get('/publisher/generate', [PublisherController::class, 'generate'])->name('publisher.generate');
    Route::post('/publisher/publish', [PublisherController::class, 'publish']);
    Route::post('/publisher/save', [PublisherController::class, 'save']);
    Route::get('/publisher/download', [PublisherController::class, 'download']);
    Route::get('/publisher/get_excesses', [PublisherController::class, 'getExcessesBySeries']);
});

Route::group(['middleware' => ['role:approver']], function () {
    Route::get('/approver', [ApproverController::class, 'index'])->name('approver.index');
    Route::put('/approver/{id}', [ApproverController::class, 'update'])->name('approver.update');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
