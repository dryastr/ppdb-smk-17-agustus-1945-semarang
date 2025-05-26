<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\StudentRegistration;
use Illuminate\Support\Facades\Auth;

class PaymentRegisController extends Controller
{
    public function index()
    {
        $payments = Payment::where('user_id', Auth::id())->with('registration')->latest()->get();
        $studentRegistration = StudentRegistration::where('user_id', auth()->id())->first();

        if (!$studentRegistration || $studentRegistration->status != 'diterima') {
            return redirect()->route('student-registrations.index')
                ->with('warning', 'Data registrasi Anda belum diterima atau anda belum melakukan registrasi.');
        }
        $payments = Payment::where('student_registration_id', $studentRegistration->id)->get();
        return view('user.payment.index', compact('payments'));
    }

    public function store(Request $request)
    {

        $studentRegistration = StudentRegistration::where('user_id', auth()->id())->first();

        $request->validate([
            'amount' => 'required|numeric',
            'metode_pembayaran' => 'required|string',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $buktiPembayaranPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPembayaranPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
        }

        Payment::create([
            'student_registration_id' => $studentRegistration->id,
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'metode_pembayaran' => $request->metode_pembayaran,
            'bukti_pembayaran' => $buktiPembayaranPath,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil dikirim, menunggu verifikasi.');
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->user_id != Auth::id()) {
            abort(403);
        }

        $request->validate([
            'amount' => 'required|numeric',
            'metode_pembayaran' => 'required|string',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->only(['amount', 'metode_pembayaran', 'keterangan']);

        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti'), $filename);
            $data['bukti_pembayaran'] = 'uploads/bukti/' . $filename;
        }

        $payment->update($data);

        return redirect()->back()->with('success', 'Data pembayaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->user_id != Auth::id()) {
            abort(403);
        }

        $payment->delete();

        return redirect()->back()->with('success', 'Pembayaran berhasil dihapus.');
    }
}
