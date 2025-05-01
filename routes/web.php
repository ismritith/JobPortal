<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\JobController;
use App\Http\Controllers\JobsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and are assigned to
| the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/jobs', [JobsController::class, 'index'])->name('jobs');
Route::get('/jobs/detail/{id}', [JobsController::class, 'detail'])->name('jobDetail');
Route::post('/apply-job', [JobsController::class, 'applyJob'])->name('applyJob');


// Dashboard Routes
Route::group(['prefix' => 'admin', 'middleware' => 'checkRole'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/users/{id}', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::post('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/jobs', [JobController::class, 'index'])->name('admin.jobs');
    Route::get('/jobs/edit/{id}', [JobController::class, 'edit'])->name('admin.jobs.edit');
    Route::put('/jobs/{id}', [JobController::class, 'update'])->name('admin.jobs.update');
    Route::delete('/jobs', [JobController::class, 'destroy'])->name('admin.jobs.destroy');
});

// Job Applicants Management
Route::get('/jobs/{id}/applications', [JobController::class, 'applications'])->name('admin.jobs.applications');
Route::middleware(['auth'])
    ->get('/admin/applications', [JobController::class, 'applications'])
    ->name('admin.applications');
Route::get('/applications/{id}', [JobController::class, 'viewApplication'])->name('admin.applications.show');
Route::put('/applications/{id}/status', [JobController::class, 'updateStatus'])->name('admin.applications.updateStatus');


// Account Routes, 
Route::prefix('account')->group(function () {

    // Registration and Login Routes (accessible to all)
    Route::get('/register', [AccountController::class, 'registration'])->name('account.registration');
    Route::post('/account/process-register', [AccountController::class, 'processRegistration'])->name('account.processRegistration');

    Route::get('/login', [AccountController::class, 'login'])->name('account.login');
    Route::post('/authenticate', [AccountController::class, 'authenticate'])->name('account.authenticate');

    // Authenticated-only Routes
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::put('/update-profile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');

        Route::get('/logout', [AccountController::class, 'logout'])->name('account.logout');
        Route::post('/update-profile-pic', [AccountController::class, 'updateProfilePic'])->name('account.updateProfilePic');

        Route::get('/create-job', [AccountController::class, 'createJob'])->name('account.createJob');
        Route::post('/save-job', [AccountController::class, 'saveJob'])->name('account.saveJob');

        Route::get('/my-jobs', [AccountController::class, 'myJobs'])->name('account.myJobs');
        Route::get('/my-jobs/edit/{jobId}', [AccountController::class, 'editJob'])->name('account.editJob');

        Route::put('/update-job/{id}', [AccountController::class, 'updateJob'])->name('account.updateJob');

        Route::post('/delete-job', [AccountController::class, 'deleteJob'])->name('account.deleteJob');
    });
});
