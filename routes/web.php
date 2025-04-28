<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\admin\DashboardController;
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


//Dashboard Routes
Route::group(['prefix' => 'admin','middleware' => 'checkRole'], function(){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

});


// Account Routes
Route::prefix('account')->group(function () {

    // Registration and Login Routes (accessible to all)
    Route::get('/register', [AccountController::class, 'registration'])->name('account.registration');
    Route::post('/process-register', [AccountController::class, 'processRegistration'])->name('account.processRegistration');
    
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