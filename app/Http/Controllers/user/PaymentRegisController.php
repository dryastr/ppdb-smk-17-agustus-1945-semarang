<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\StudentRegistration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentRegisController extends Controller
{
    public function index()
    {
        $registration = StudentRegistration::where('user_id', auth()->id())->first();

        if (!$registration) {
            return view('user.payment.index', ['payments' => collect()]);
        }

        $allPayments = Payment::where('user_id', auth()->id())
            ->where('student_registration_id', $registration->id)
            ->get();

        $hasSuccessfulOrPendingPayment = $allPayments->whereNotIn('status', ['ditolak', 'gagal'])->isNotEmpty();

        $paymentsToShow = collect();

        if ($hasSuccessfulOrPendingPayment) {
            $paymentsToShow = $allPayments->whereNotIn('status', ['ditolak', 'gagal']);
        } else {
            $paymentsToShow = $allPayments;
        }

        $payments = $paymentsToShow->sortByDesc('created_at');

        return view('user.payment.index', compact('payments'));
    }

    public function uploadBukti(Request $request, Payment $payment)
    {
        if ($payment->user_id != auth()->id()) {
            abort(403);
        }

        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'metode_pembayaran' => 'required|string',
        ]);

        if ($payment->bukti_pembayaran) {
            Storage::delete($payment->bukti_pembayaran);
        }

        $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        $payment->update([
            'bukti_pembayaran' => $buktiPath,
            'metode_pembayaran' => $request->metode_pembayaran,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload, menunggu verifikasi admin.');
    }

    public function destroy(Payment $payment)
    {
        if ($payment->user_id != auth()->id()) {
            abort(403);
        }

        if ($payment->status != 'pending') {
            return redirect()->back()->with('error', 'Pembayaran yang sudah diverifikasi tidak bisa dihapus.');
        }

        if ($payment->bukti_pembayaran) {
            Storage::delete($payment->bukti_pembayaran);
        }

        $payment->delete();

        return redirect()->back()->with('success', 'Pembayaran berhasil dihapus.');
    }
}
