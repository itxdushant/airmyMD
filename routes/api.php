<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PassportAuthController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\DoctorController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ChatController;

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


// Route::post('register', [PassportAuthController::class, 'register']);
Route::post('register-otp', [PassportAuthController::class, 'registerOtp']);
Route::post('register-verify', [PassportAuthController::class, 'registerVerify']);
Route::post('login-otp', [PassportAuthController::class, 'loginOtp']);
Route::post('login-verify', [PassportAuthController::class, 'loginVerify']);
  
Route::middleware('auth:api')->group(function () {
    
    Route::get('user', [PassportAuthController::class, 'userInfo']);

    Route::post('profile-location', [ProfileController::class, 'location']);
    Route::post('profile', [ProfileController::class, 'profile']);
    Route::post('allergies', [ProfileController::class, 'allergies']);
    Route::post('medical-history', [ProfileController::class, 'medicalHistory']);
    Route::post('surgeries', [ProfileController::class, 'surgeries']);
    Route::post('medications', [ProfileController::class, 'medications']);
    Route::post('sexual-health', [ProfileController::class, 'sexualHealth']);
    Route::post('habits', [ProfileController::class, 'habits']);
    Route::post('general', [ProfileController::class, 'general']);
    Route::post('tobacco', [ProfileController::class, 'tobacco']);
    Route::post('drug', [ProfileController::class, 'drug']);
    Route::post('insurance', [ProfileController::class, 'insurance']);
    Route::post('insurance/{id}', [ProfileController::class, 'insuranceDetails']);
    Route::post('delete-insurance', [ProfileController::class, 'insuranceDelete']);
    Route::get('insurance-list', [ProfileController::class, 'cardList']);
    
    Route::post('alcohol', [ProfileController::class, 'alcohol']);

    Route::post('doctor-list', [DoctorController::class, 'list']);
    Route::get('doctor-view/{id}', [DoctorController::class, 'view']);
    Route::post('specializations', [DoctorController::class, 'specializations']);
    Route::post('booking', [BookingController::class, 'booking']);
    Route::get('appointments', [BookingController::class, 'appointments']);
    Route::post('booking-cancel', [BookingController::class, 'reject']);
    Route::post('review', [BookingController::class, 'review']);
    
    /* Booking slots */
    Route::post('slots', [BookingController::class, 'slots']);
    
    /* Payment Route */
    Route::post('add-card', [PaymentController::class, 'AddCard']);
    /* Card Listing */
    Route::get('card-listing', [PaymentController::class, 'CardListing']);
    Route::post('card-delete', [PaymentController::class, 'DeleteCard']);
    
    
    /* Profile APIs*/
    Route::get('get-allergies', [ProfileController::class, 'GetAllergies']);
    Route::get('get-medication', [ProfileController::class, 'GetMedication']);
    Route::get('get-cigarette', [ProfileController::class, 'GetCigarette']);

    /* Chat APIs */
    Route::get('conversion-list', [ChatController::class, 'index']);
    
});