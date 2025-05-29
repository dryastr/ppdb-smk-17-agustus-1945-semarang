<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Carbon\Carbon;

class PrintPaymentRegisController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melihat riwayat pembayaran.');
        }

        $payments = auth()->user()->payments()->orderBy('created_at', 'desc')->get();

        return view('user.print-payments.index', compact('payments'));
    }

    public function showReceipt($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melihat kuitansi.');
        }

        $payment = auth()->user()->payments()
            ->with(['user', 'studentRegistration'])
            ->findOrFail($id);


        return view('user.print-payments.receipt', compact('payment'));
    }
}
