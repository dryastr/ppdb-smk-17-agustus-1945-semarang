<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentRegistration;
use App\Models\PpdbStage; // Import model PpdbStage
use Illuminate\Support\Carbon; // Untuk menggunakan Carbon::now()

class ManageStudentRegisController extends Controller
{
    public function index()
    {
        $registrations = StudentRegistration::with('user', 'ppdbStage')->latest()->get();
        $ppdbStages = PpdbStage::orderBy('order')->get();

        return view('admin.manage-student-registrations.index', compact('registrations', 'ppdbStages'));
    }

    public function updateStatus(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'status' => 'required|in:diperiksa,diterima,ditolak,menunggu',
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

            'ppdb_stage_id' => 'nullable|exists:ppdb_stages,id',
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

        if ($request->filled('ppdb_stage_id')) {
            $newStageId = (int) $request->ppdb_stage_id;

            if ($registration->ppdb_stage_id !== $newStageId) {
                $registration->ppdb_stage_id = $newStageId;
            }
        } else {
            if ($registration->ppdb_stage_id !== null) {
                $registration->ppdb_stage_id = null;
            }
        }

        $registration->save();

        return redirect()->back()->with('success', 'Data pendaftaran berhasil diperbarui.');
    }
}
