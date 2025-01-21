<?php

use App\Notifications\UserCreate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPassword;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\FilePondController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageSpeedController;
use App\Http\Controllers\DictionaryController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ListingOfferController;
use App\Http\Controllers\PrivateFilesController;
use App\Http\Controllers\RestoreStateController;
use App\Http\Controllers\RememberStateController;
use App\Http\Controllers\RealtorListingController;
use App\Http\Controllers\RealtorListingImageController;

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
Route::get('/', [DashboardController::class, 'index'])->name('home');

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

Route::get('user/{user}', [UserController::class, 'show'])
    ->name('user.show')
    ->withTrashed();

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

Route::get('client/{client}', [ClientController::class, 'show'])
    ->name('client.show')
    ->withTrashed();

Route::name('client.restore')->put('client/{client}/restore',
        [ClientController::class, 'restore'])->withTrashed();

//Email Verification
Route::get('/email/verify', function () {
    if(!Auth::user() || !Auth::user()->hasVerifiedEmail()){
        return inertia('Auth/Verify');
    }

    return redirect()->intended();
})->middleware('auth')->name('verification.notice');

//Password Reset
//Route::get('/forgot-password', [ResetPassword::class, 'create'])->name('password.request');
Route::post('/forgot-password', [ForgotPassword::class, 'store'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPassword::class, 'edit'])->name('password.reset');
Route::post('/reset-password', [ResetPassword::class, 'update'])->name('password.update');

//ExpansesController
Route::resource('expenses', ExpensesController::class)
    ->only(['index', 'create', 'edit', 'destroy', 'store']);

Route::put('expenses/{expense}/restore', [ExpensesController::class, 'restore'])
    ->name('expenses.restore')
    ->withTrashed();

Route::delete('expenses/{expense}/file', [ExpensesController::class, 'remove'])
    ->name('expenses.remove');

Route::post('expenses/{expense}', [ExpensesController::class, 'update'])
    ->name('expenses.update');


//PrivateFilesController
Route::get('private/files/{catalog}/{file}', PrivateFilesController::class)
    ->name('private.files');

//ProjectController
Route::resource('projects', ProjectController::class)
    ->except(['show']);

Route::put('projects/{project}/restore', [ProjectController::class, 'restore'])
    ->name('projects.restore')
    ->withTrashed();

Route::get('projects/{project}', [ProjectController::class, 'show'])
    ->name('projects.show')
    ->withTrashed();

Route::post('projects/{project}/status', [ProjectController::class, 'status'])
    ->name('projects.status');

Route::post('projects/{project}/upload', [ProjectController::class, 'upload'])
    ->name('projects.upload');

// Route::delete('projects/{project}/images/{image}', [ProjectController::class, 'images_delete'])
//     ->name('projects.images.destroy');

//DictionaryController
Route::get('dictionary/{table}', [DictionaryController::class, 'index'])
    ->name('dictionary.index');

//FilePondController
Route::resource('filepond', FilePondController::class)
    ->only(['store', 'destroy']);

Route::post('/remember-state', [RememberStateController::class, 'store'])
    ->name('remember.state');

Route::get('/restore-state', [RestoreStateController::class, 'index'])
    ->name('restore.state');

Route::resource('incomes', IncomeController::class)
    ->except(['show']); 

Route::get('incomes/{income}', [IncomeController::class, 'show'])
    ->name('incomes.show')
    ->withTrashed();

Route::put('incomes/{income}/restore', [IncomeController::class, 'restore'])
    ->name('incomes.restore')
    ->withTrashed();

Route::put('incomes/{income}/settle', [IncomeController::class, 'settle'])
    ->name('incomes.settle');