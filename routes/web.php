<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\ManagePaymentRegisController;
use App\Http\Controllers\admin\ManageStudentRegisController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\user\PaymentRegisController;
use App\Http\Controllers\user\StudentRegisController;
use App\Http\Controllers\user\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role->name === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('home');
        }
    }
    return redirect()->route('login');
})->name('auth.login');

Auth::routes(['middleware' => ['redirectIfAuthenticated']]);


Route::middleware(['auth', 'role.admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::resource('manage-student-registrations', ManageStudentRegisController::class);
    Route::post('manage-student-registrations/{id}/update-status', [ManageStudentRegisController::class, 'updateStatus'])->name('student-registrations.updateStatus');
    Route::get('manage-payment-registration', [ManagePaymentRegisController::class, 'index'])->name('manage-payment.index');
    Route::post('manage-payment-registration/update-status/{payment}', [ManagePaymentRegisController::class, 'updateStatus'])->name('manage-payment.updateStatus');
});

Route::middleware(['auth', 'role.user'])->group(function () {
    Route::get('/home', [UserController::class, 'index'])->name('home');

    Route::resource('student-registrations', StudentRegisController::class);
    Route::put('/student-registrations/{student_registration}', [StudentRegisController::class, 'update'])->name('student-registrations.update');


    Route::resource('payment-registration', PaymentRegisController::class);
});
