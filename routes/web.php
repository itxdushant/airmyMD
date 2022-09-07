<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
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
Route::get('/', [AuthenticatedSessionController::class, 'create'])
->middleware(['guest:'.config('fortify.guard')]);
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
->middleware(['guest:'.config('fortify.guard')])
->name('login');
// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
        
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::group(['middleware' => 'is.admin'], function () {
    Route::get('/', [ AdminController::class, 'userList']);
    Route::get('/dashboard', [ AdminController::class, 'userList'])->name('admindashboard');
    Route::get('/doctors', [ AdminController::class, 'userList'])->name('doctors');
    Route::post('/approve-doctor', [ AdminController::class, 'doctorApprove']);
    Route::post('/disapprove-doctor', [ AdminController::class, 'doctorDisapprove']);
    Route::get('/doctor-filter', [ AdminController::class, 'doctorFilter']);
    Route::get('/doctor-details/{id}', [ AdminController::class, 'DoctorDetails']);
});


Route::middleware([
    'is.doctor',
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Route::get('/dashboard', function () {
    //     return Inertia::render('Dashboard');
    // });
    // Route::get('/', function () {
    //     return Inertia::render('Dashboard');
    // })->name('dashboard');
    Route::get('/', [ DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [ DashboardController::class, 'index'])->name('dashboard');
    Route::get('/chat', [ ChatController::class, 'index'])->name('chat');
    Route::get('/chat/{i}', [ ChatController::class, 'index'])->name('chat');
    Route::get('/conversion', [ ChatController::class, 'GetConversion'])->name('conversion');
    Route::post('/sent-message', [ ChatController::class, 'sendMessage'])->name('send.message');
    Route::post('/get-messages', [ ChatController::class, 'getMessage'])->name('get.message');
    Route::post('/search-patient', [ ChatController::class, 'searchPatient'])->name('search.patient');
    
    Route::get('/account', [ AccountController::class, 'show'])->name('account.show');
    Route::post('/update-account', [ AccountController::class, 'update'])->name('account.update');
    Route::post('/update-password', [ AccountController::class, 'updatePassword'])->name('account.password');

    Route::match(['get', 'post'], '/availability', [ ProfileController::class, 'availability'])->name('availability');
    Route::get('/patients', [ BookingController::class, 'patients'])->name('patients');
    Route::get('/patients-list', [ BookingController::class, 'patientsList'])->name('patients.list');
    Route::get('/patient/{id}', [ AccountController::class, 'patientDetails'])->name('patient.details');
    Route::get('/reviews', [ BookingController::class, 'reviews'])->name('reviews');
    Route::get('/reviews-list', [ BookingController::class, 'reviewsList'])->name('review-list');
    
    Route::get('/appointments', [ BookingController::class, 'appointments'])->name('appointments');
    Route::get('/appointments-list', [ BookingController::class, 'appointmentsList'])->name('appointments.list');

    Route::get('/appointment-requests', [ BookingController::class, 'appointmentRequest'])->name('appointment-history');
    Route::get('/appointments-request-list', [ BookingController::class, 'appointmentsRequest'])->name('appointments.history');

    Route::get('/appointment-history', [ BookingController::class, 'appointmentHistory'])->name('appointment-requests');
    Route::get('/appointments-history-list', [ BookingController::class, 'appointmentsHistory'])->name('appointments.requests');
    
    Route::post('/get-appointments', [ BookingController::class, 'getApointments'])->name('get.appointments');
    
    Route::get('/get-appointments-count', [ BookingController::class, 'getApointmentCount'])->name('get.appointments.count');

    Route::post('/booking-accept', [ BookingController::class, 'accept'])->name('booking.accept');
    Route::post('/booking-reject', [ BookingController::class, 'reject'])->name('booking.reject');
    Route::post('/booking-complete', [ BookingController::class, 'complete'])->name('booking.complete');
    
    Route::get('/transactions', [ BookingController::class, 'transactions'])->name('transactions');
    Route::get('/transactions-list', [ BookingController::class, 'transactionsList'])->name('transactions.list');

});

Route::get('/logout', [ AccountController::class, 'logout'])->name('logout');

Route::get('/terms', [ AccountController::class, 'terms'])->name('terms');
Route::get('/privacy-policy', [ AccountController::class, 'privacyPolicy'])->name('privacy.policy');

Route::post('/connected-account', [ AccountController::class, 'connectedAccount'])->name('connected-account');
Route::post('/save-bank', [ AccountController::class, 'saveBank'])->name('save.bank');

Route::get('/banking-info-success', [ AccountController::class, 'bankingInfoSuccess'])->name('banking.info.success');
Route::get('/banking-info-error', [ AccountController::class, 'bankingInfoError'])->name('banking.info.error');

Route::get('/profile', [ ProfileController::class, 'show'])->name('profile.show');
Route::post('/update-profile', [ ProfileController::class, 'updateProfile'])->name('profile.update');
Route::match(['get', 'post'], '/profile',[ ProfileController::class, 'show'])->name('profile.show');
Route::match(['get', 'post'], '/create-profile',[ ProfileController::class, 'create'])->name('profile.create');
Route::match(['get', 'post'], '/profile-bio', [ ProfileController::class, 'bio'])->name('profile.bio');
Route::match(['get', 'post'], '/areas-specialization', [ ProfileController::class, 'specialization'])->name('profile.specialization');

Route::match(['get', 'post'], '/patients-gender', [ ProfileController::class, 'patientsGender'])->name('profile.patients-gender');
Route::match(['get', 'post'], '/offer-telemedicine', [ ProfileController::class, 'offerTelemedicine'])->name('profile.offer-telemedicine');
Route::match(['get', 'post'], '/insurance-acceptance', [ ProfileController::class, 'insuranceAcceptance'])->name('profile.insurance-acceptance');
Route::match(['get', 'post'], '/tags-apply', [ ProfileController::class, 'tagsApply'])->name('profile.tags-apply');
Route::match(['get', 'post'], '/appointment-fee', [ ProfileController::class, 'appointmentFee'])->name('profile.fees');



