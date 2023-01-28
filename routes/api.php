<?php

use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\Loan\LoanController as AdminLoanController;
use App\Http\Controllers\Customer\Auth\LoginController;
use App\Http\Controllers\Customer\Loan\LoanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/admin/user', function (Request $request) {
    return $request->user();
});

Route::controller(LoginController::class)->group(function(){
    Route::post('login', 'login');
});

Route::controller(AdminLoginController::class)->group(function(){
    Route::post('admin/login', 'login');
});

Route::controller(LoanController::class)->group(function(){
    Route::group(['prefix' => 'loan', 'middleware' => 'auth:sanctum'], function(){
        Route::post('apply', 'apply');
        Route::get('list', 'list');
        Route::get('{id}', 'details');
    });
});

Route::controller(AdminLoanController::class)->group(function(){
    Route::group(['prefix' => 'admin/loan', 'middleware' => 'auth:sanctum'], function(){
        Route::patch('{id}', 'update');
    });
});
