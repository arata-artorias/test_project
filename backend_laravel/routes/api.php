<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\VerifyEmailController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\ResetPasswordController;
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

// Register/Login
Route::group(['prefix' => 'users', 'middleware' => 'CORS'], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register.user');
    Route::post('/login', [AuthController::class, 'login'])->name('login.user');
});

// Verify Email
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
Route::get('/email/verify/{msg}', [VerifyEmailController::class, 'showPage']);

//Forget Password
Route::post('forgot-password', [ForgotPasswordController::class, '__invoke']);
// Route::post('forgot-password', [AuthController::class, 'forgot_password']);
// Route::group(['middleware' => 'auth:api'], function () {
//     Route::post('change-password', [AuthController::class, 'change_password']);
// });
Route::post('change-password', [ResetPasswordController::class, '__invoke']);

// Companies
Route::group(['prefix' => 'companies', 'middleware' => ['CORS', 'auth:api']], function ($router) {
    Route::get('/search/{data?}', [CompanyController::class, 'getCompanies'])->name('index.companies');
    Route::post('/add', [CompanyController::class, 'addCompany'])->name('add.company');
    Route::post('/add-favourite', [CompanyController::class, 'addFavouriteCompany'])->name('add.favourite.company');
    Route::get('/favourite-list', [CompanyController::class, 'viewFavouriteCompaniesList'])->name('index.favourite.companies');
});