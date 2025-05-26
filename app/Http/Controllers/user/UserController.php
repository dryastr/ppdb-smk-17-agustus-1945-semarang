<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\StudentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $registration = StudentRegistration::where('user_id', Auth::id())->first();

        $payment = null;
        if ($registration) {
            $payment = Payment::where('student_registration_id', $registration->id)->latest()->first();
        }

        return view('user.dashboard', compact('registration', 'payment'));
    }
}
