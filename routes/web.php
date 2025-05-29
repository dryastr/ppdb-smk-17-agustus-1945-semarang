<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\FaqController;
use App\Http\Controllers\admin\HeroSectionController;
use App\Http\Controllers\admin\ManageHistoriPaymentController;
use App\Http\Controllers\admin\ManagePaymentRegisController;
use App\Http\Controllers\admin\ManageStudentRegisController;
use App\Http\Controllers\admin\manageUserController;
use App\Http\Controllers\admin\PPDBStagesController;
use App\Http\Controllers\admin\RegistrationFlowsController;
use App\Http\Controllers\admin\RekeningController;
use App\Http\Controllers\HomeController;
// use App\Http\Controllers\ManageProfile;
use App\Http\Controllers\ManageProfileController;
use App\Http\Controllers\user\HistoriPaymentRegisController;
use App\Http\Controllers\user\PaymentRegisController;
use App\Http\Controllers\user\PrintPaymentRegisController;
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
    Route::resource('manage-payment-registration', ManagePaymentRegisController::class);
    Route::post('manage-payment-registration/update-status/{payment}', [ManagePaymentRegisController::class, 'updateStatus'])->name('manage-payment.updateStatus');
    Route::resource('rekenings', RekeningController::class)
        ->except(['show'])
        ->names('rekenings');
    Route::get('/manage-payments', [ManageHistoriPaymentController::class, 'index'])->name('manage-history.index');
    Route::get('/manage-payments/export-excel', [ManageHistoriPaymentController::class, 'exportExcel'])->name('manage-history.exportExcel');
    Route::get('/payments-print/{payment}', function (App\Models\Payment $payment) {
        return response()->json($payment->load(['studentRegistration', 'rekening']));
    })->name('admin.payment.show');
    Route::get('/manage-users', [manageUserController::class, 'index'])->name('manage-user.index');
    Route::resource('faqs', FaqController::class)->except([
        'create',
        'show',
        'edit'
    ]);
    Route::get('/hero-section', [HeroSectionController::class, 'index'])->name('admin.hero_section.index');
    Route::post('/hero-section', [HeroSectionController::class, 'storeOrUpdate'])->name('admin.hero_section.store_or_update');
    Route::resource('registration-flows', RegistrationFlowsController::class)->except(['create', 'show', 'edit']);
    Route::resource('ppdb-stages', PPDBStagesController::class)->except(['create', 'show', 'edit']);
    Route::get('/profile-admin', [ManageProfileController::class, 'index'])->name('profile-admin.index');
    Route::put('/profile-admin', [ManageProfileController::class, 'update'])->name('profile-admin.update');
});

Route::middleware(['auth', 'role.user'])->group(function () {
    Route::get('/home', [UserController::class, 'index'])->name('home');

    Route::resource('student-registrations', StudentRegisController::class);
    Route::put('/student-registrations/{student_registration}', [StudentRegisController::class, 'update'])->name('student-registrations.update');
    Route::prefix('payment-registration')->group(function () {
        Route::get('/', [PaymentRegisController::class, 'index'])
            ->name('payment-registration.index');
        Route::post('/upload/{payment}', [PaymentRegisController::class, 'uploadBukti'])
            ->name('payment-registration.upload');
        Route::delete('/{payment}', [PaymentRegisController::class, 'destroy'])
            ->name('payment-registration.destroy');
    });
    Route::get('/history-payment-registration', [HistoriPaymentRegisController::class, 'index'])
        ->name('history-payment-registration.index');
    Route::get('/profile-user', [ManageProfileController::class, 'index'])->name('profile-user.index');
    Route::put('/profile-user', [ManageProfileController::class, 'update'])->name('profile.update');
    Route::get('/print-payments', [PrintPaymentRegisController::class, 'index'])->name('print-payments.index');
    Route::get('/print-payments/{id}/receipt', [PrintPaymentRegisController::class, 'showReceipt'])->name('print-payments.receipt');
});
