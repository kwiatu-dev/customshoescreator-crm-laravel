<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPassword;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\PageSpeedController;
use App\Http\Controllers\ListingOfferController;
use App\Http\Controllers\RealtorListingController;
use App\Http\Controllers\RealtorListingImageController;
use App\Notifications\UserCreate;

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

//IndexController
Route::get('/', [IndexController::class, 'index'])->name('home');
Route::get('/hello', [IndexController::class, 'show']);

//ListingController
Route::resource('listing', ListingController::class)->only('index', 'show');

//ListingOfferController
Route::resource('listing.offer', ListingOfferController::class)->only(['store']);

//AuthController
Route::get('login', [AuthController::class, 'create'])->name('login');
Route::post('login', [AuthController::class, 'store'])->name('login.store');
Route::delete('logout', [AuthController::class, 'destroy'])->name('logout');

//UserAccountController
Route::resource('user', UserController::class)
    ->only(['create', 'store', 'index', 'edit', 'destroy', 'update']);
Route::put('user/{user}/restore', [UserController::class, 'restore'])
    ->name('user.restore')
    ->withTrashed();

//PageSpeedController & RealtorListingImageController
Route::get('/audit', [PageSpeedController::class, 'index']);
Route::post('/audit', [PageSpeedController::class, 'audit']);

//RealtorListingController
Route::prefix('realtor')->name('realtor.')->group(function() {
    Route::name('listing.restore')
        ->put(
            'listing/{listing}/restore',
            [RealtorListingController::class, 'restore']
        )->withTrashed();

    Route::resource('listing', RealtorListingController::class)
        ->only(['index', 'destroy', 'edit', 'update', 'create', 'store', 'show'])
        ->withTrashed();

    Route::resource('listing.image', RealtorListingImageController::class)
        ->only(['create', 'store', 'destroy']);
});

//ClientController
Route::resource('client', ClientController::class)->except(['show']);

Route::name('client.restore')->put('client/{client}/restore',
        [ClientController::class, 'restore']
    )->withTrashed();

//Email Verification
Route::get('/email/verify', function () {
    return inertia('Auth/Verify');
})->middleware('auth')->name('verification.notice');

//Password Reset
//Route::get('/forgot-password', [ResetPassword::class, 'create'])->name('password.request');
Route::post('/forgot-password', [ForgotPassword::class, 'store'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPassword::class, 'edit'])->name('password.reset');
Route::post('/reset-password', [ResetPassword::class, 'update'])->name('password.update');

//ExpansesController
Route::resource('expenses', ExpensesController::class)
    ->only(['index', 'create', 'edit', 'destroy']);

Route::put('expenses/{expense}/restore', [ExpensesController::class, 'restore'])
    ->name('expenses.restore')
    ->withTrashed();