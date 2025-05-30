<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPassword;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\FilePondController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DictionaryController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\InvestmentRepaymentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationSeenController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\PrivateFilesController;
use App\Http\Controllers\RestoreStateController;
use App\Http\Controllers\RememberStateController;
use App\Http\Controllers\UserEventsController;

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


Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('login', [AuthController::class, 'create'])->name('login');
Route::post('login', [AuthController::class, 'store'])->name('login.store');
Route::delete('logout', [AuthController::class, 'destroy'])->name('logout');


Route::resource('user', UserController::class)
    ->only(['create', 'store', 'index', 'edit', 'destroy', 'update']);

Route::get('user/{user}', [UserController::class, 'show'])
    ->name('user.show')
    ->withTrashed();

Route::put('user/{user}/restore', [UserController::class, 'restore'])
    ->name('user.restore')
    ->withTrashed();

Route::resource('client', ClientController::class)->except(['show']);

Route::get('client/{client}', [ClientController::class, 'show'])
    ->name('client.show')
    ->withTrashed();

Route::name('client.restore')->put('client/{client}/restore',
        [ClientController::class, 'restore'])->withTrashed();

Route::get('/email/verify', function () {
    if(!Auth::user() || !Auth::user()->hasVerifiedEmail()){
        return inertia('Auth/Verify');
    }

    return redirect()->intended();
})->middleware('auth')->name('verification.notice');

Route::post('/forgot-password', [ForgotPassword::class, 'store'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPassword::class, 'edit'])->name('password.reset');
Route::post('/reset-password', [ResetPassword::class, 'update'])->name('password.update');

Route::resource('expenses', ExpensesController::class)
    ->only(['index', 'create', 'edit', 'destroy', 'store']);

Route::put('expenses/{expense}/restore', [ExpensesController::class, 'restore'])
    ->name('expenses.restore')
    ->withTrashed();

Route::delete('expenses/{expense}/file', [ExpensesController::class, 'remove'])
    ->name('expenses.remove');

Route::post('expenses/{expense}', [ExpensesController::class, 'update'])
    ->name('expenses.update');

Route::get('private/files/{catalog}/{file}', PrivateFilesController::class)
    ->name('private.files');

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

Route::get('dictionary/{table}', [DictionaryController::class, 'index'])
    ->name('dictionary.index');

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

Route::resource('investments', InvestmentController::class)
    ->except(['show']); 

Route::get('investments/{investment}', [InvestmentController::class, 'show'])
    ->name('investments.show')
    ->withTrashed();

Route::put('investments/{investment}/restore', [InvestmentController::class, 'restore'])
    ->name('investments.restore')
    ->withTrashed();

Route::prefix('investments/{investment}')->group(function () {
    Route::resource('repayments', InvestmentRepaymentController::class)
        ->except(['show'])
        ->shallow(); 

    Route::put('repayments/{repayment}/restore', [InvestmentRepaymentController::class, 'restore'])
        ->name('repayments.restore')
        ->withTrashed();
});

Route::resource('organizer', OrganizerController::class)
    ->only('index');

Route::resource('user-events', UserEventsController::class)
    ->except(['index', 'show', 'create']);

Route::get('user-events/{user_event}', [UserEventsController::class, 'show'])
    ->name('user-events.show')
    ->withTrashed();

Route::put('user-events/{user_event}/restore', [UserEventsController::class, 'restore'])
    ->name('user-events.restore')
    ->withTrashed();

Route::resource('notifications', NotificationController::class)
    ->only(['index', 'destroy']);

Route::prefix('api')->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('kpi', [DashboardController::class, 'getKpi'])
            ->name('dashboard.kpi');

        Route::get('top-users', [DashboardController::class, 'getTopUsersByIncome'])
            ->name('dashboard.top-users');

        Route::get('top-projects', [DashboardController::class, 'getTopProjectsByIncome'])
            ->name('dashboard.top-projects');

        Route::get('projects-type-breakdown', [DashboardController::class, 'getProjectTypeBreakdown'])
            ->name('dashboard.projects-type-breakdown');

        Route::get('project-years', [DashboardController::class, 'getProjectYears'])
            ->name('dashboard.project-years');

        Route::get('income-years', [DashboardController::class, 'getIncomeYears'])
            ->name('dashboard.income-years');

        Route::get('monthly-financial-stats', [DashboardController::class, 'getMonthlyFinancialStats'])
            ->name('dashboard.monthly-financial-stats');

        Route::get('monthly-completed-projects-count', [DashboardController::class, 'getMonthlyCompletedProjectsCount'])
            ->name('dashboard.monthly-completed-projects-count');

        Route::get('monthly-new-projects-count', [DashboardController::class, 'getMonthlyNewProjectsCount'])
            ->name('dashboard.monthly-new-projects-count');
    });
});

Route::put('notifications/{notification}/seen', NotificationSeenController::class)
    ->name('notifications.seen');


