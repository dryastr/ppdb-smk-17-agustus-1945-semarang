<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class ManagePaymentRegisController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['user', 'studentRegistration'])->latest()->get();
        return view('admin.manage-payment.index', compact('payments'));
    }

    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,dibayar,gagal,ditolak',
            'keterangan' => 'nullable|string',
        ]);

        if (in_array($request->status, ['gagal', 'ditolak']) && !$request->filled('keterangan')) {
            return redirect()->back()->with('error', 'Keterangan wajib diisi jika status gagal atau ditolak.');
        }

        $payment->status = $request->status;
        $payment->keterangan = in_array($request->status, ['gagal', 'ditolak']) ? $request->keterangan : null;
        $payment->save();

        return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}
