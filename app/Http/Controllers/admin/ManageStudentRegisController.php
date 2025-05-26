<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentRegistration;

class ManageStudentRegisController extends Controller
{
    public function index()
    {
        $registrations = StudentRegistration::with('user')->latest()->get();

        return view('admin.manage-student-registrations.index', compact('registrations'));
    }

    public function updateStatus(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'status' => 'required|in:diterima,ditolak,menunggu',
            'keterangan_status' => 'nullable|string|max:255',

            'tato' => 'nullable|boolean',
            'tindik' => 'nullable|boolean',
            'buta_warna' => 'nullable|boolean',
            // 'tinggi_badan' => 'nullable|numeric',
            // 'berat_badan' => 'nullable|numeric',
            'hasil_tes' => 'nullable|string|max:255',

            'keterangan_fisik_tato' => 'nullable|string|max:255',
            'keterangan_fisik_tindik' => 'nullable|string|max:255',
            'keterangan_fisik_butawarna' => 'nullable|string|max:255',
            'keterangan_fisik_tinggi_berat' => 'nullable|string|max:255',
        ]);

        $registration = StudentRegistration::findOrFail($id);

        $registration->status = $request->status;
        $registration->keterangan_status = $request->keterangan_status;

        $registration->tato = $request->input('tato', 0);
        $registration->tindik = $request->input('tindik', 0);
        $registration->buta_warna = $request->input('buta_warna', 0);

        // $registration->tinggi_badan = $request->tinggi_badan;
        // $registration->berat_badan = $request->berat_badan;
        $registration->hasil_tes = $request->hasil_tes;

        $registration->keterangan_fisik_tato = $request->keterangan_fisik_tato;
        $registration->keterangan_fisik_tindik = $request->keterangan_fisik_tindik;
        $registration->keterangan_fisik_butawarna = $request->keterangan_fisik_butawarna;
        $registration->keterangan_fisik_tinggi_berat = $request->keterangan_fisik_tinggi_berat;

        $registration->save();

        return redirect()->back()->with('success', 'Data pendaftaran berhasil diperbarui.');
    }
}
