<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\StudentRegistration;
use App\Models\PpdbStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $registration = StudentRegistration::where('user_id', Auth::id())->with('ppdbStage')->first();

        $payment = null;
        if ($registration) {
            $payment = Payment::where('student_registration_id', $registration->id)->latest()->first();
        }

        $activeStages = PpdbStage::where('is_active', true)->orderBy('order')->get();

        $currentGlobalStage = PpdbStage::where('is_active', true)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->orderBy('order')
            ->first();

        return view('user.dashboard', compact('registration', 'payment', 'activeStages', 'currentGlobalStage'));
    }
}
