<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\StudentRegistration;
use Illuminate\Support\Facades\Auth;

class HistoriPaymentRegisController extends Controller
{
    public function index()
    {
        $registration = StudentRegistration::where('user_id', auth()->id())->first();

        if (!$registration) {
            return view('user.history.index', ['payments' => collect()]);
        }

        $payments = Payment::where('student_registration_id', $registration->id)
            ->with('rekening')
            ->latest()
            ->get();

        return view('user.history.index', compact('payments'));
    }
}
