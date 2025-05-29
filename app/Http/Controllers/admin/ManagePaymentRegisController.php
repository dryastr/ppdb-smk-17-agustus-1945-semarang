<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Rekening;
use App\Models\StudentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ManagePaymentRegisController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['user', 'studentRegistration', 'rekening'])
            ->latest()
            ->get();

        $registrations = StudentRegistration::whereDoesntHave('payment', function ($query) {
            $query->whereIn('status', ['pending', 'dibayar']);
        })->orWhereHas('payment', function ($query) {
            $query->whereIn('status', ['ditolak', 'gagal']);
        })->get();

        $rekenings = Rekening::all();

        return view('admin.manage-payment.index', compact('payments', 'registrations', 'rekenings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_registration_id' => 'required|exists:student_registrations,id',
            'rekening_id' => 'required|exists:rekenings,id',
            'amount' => 'required|numeric|min:1000',
        ]);

        $registration = StudentRegistration::findOrFail($request->student_registration_id);

        Payment::create([
            'student_registration_id' => $request->student_registration_id,
            'user_id' => $registration->user_id,
            'rekening_id' => $request->rekening_id,
            'amount' => $request->amount,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil ditambahkan untuk siswa.');
    }

    public function createPayment(Request $request)
    {
        $request->validate([
            'student_registration_id' => 'required|exists:student_registrations,id',
            'rekening_id' => 'required|exists:rekenings,id',
            'amount' => 'required|numeric|min:1000',
        ]);

        $registration = StudentRegistration::findOrFail($request->student_registration_id);

        Payment::create([
            'student_registration_id' => $request->student_registration_id,
            'user_id' => $registration->user_id,
            'rekening_id' => $request->rekening_id,
            'amount' => $request->amount,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil ditambahkan untuk siswa.');
    }

    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,dibayar,gagal,ditolak',
            'keterangan' => 'nullable|string',
        ]);

        if (in_array($request->status, ['gagal', 'ditolak'])) {
            $request->validate(['keterangan' => 'required|string']);
        }

        $payment->status = $request->status;
        $payment->keterangan = in_array($request->status, ['gagal', 'ditolak'])
            ? $request->keterangan
            : null;
        $payment->save();

        return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    public function destroy(Payment $manage_payment_registration)
    {
        if ($manage_payment_registration->bukti_pembayaran) {
            Storage::delete($manage_payment_registration->bukti_pembayaran);
        }

        $manage_payment_registration->delete();

        return redirect()->back()->with('success', 'Pembayaran berhasil dihapus.');
    }
}
