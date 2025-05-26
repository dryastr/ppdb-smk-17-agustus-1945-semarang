<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\StudentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentRegisController extends Controller
{
    public function index()
    {
        $registrations = StudentRegistration::where('user_id', Auth::id())->latest()->paginate(10);
        return view('user.student_registrations.index', compact('registrations'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'nama_pengantar' => 'nullable|string|max:255',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'golongan_darah' => 'nullable|string|max:10',
            'agama' => 'required|string|max:255',
            'pelajaran_agama' => 'nullable|string|max:255',
            'alamat' => 'required|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'jumlah_saudara' => 'nullable|integer|min:0',

            'sekolah_asal' => 'required|string|max:255',
            'nisn' => 'nullable|string|max:20',
            'no_sttb' => 'nullable|string|max:255',
            'tahun_sttb' => 'nullable|string|max:4',
            'alamat_sekolah' => 'nullable|string',
            'ijazah_terakhir' => 'nullable|string|max:255',
            'no_seri_ijazah' => 'nullable|string|max:255',
            'tahun_lulus' => 'nullable|digits:4',

            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'keadaan_ayah' => 'required|in:Masih Hidup,Sudah Meninggal',
            'keadaan_ibu' => 'required|in:Masih Hidup,Sudah Meninggal',
            'alamat_orang_tua' => 'nullable|string',
            'nama_wali' => 'nullable|string|max:255',
            'alamat_wali' => 'nullable|string',
            'telepon_wali' => 'nullable|string|max:20',
            'penerima_pip' => 'nullable|boolean',

            'jurusan' => 'required|in:Otomotif,DKV,Farmasi,Technopreneur',
            'mengetahui_dari' => 'nullable|string|max:255',

            'foto_3x4' => 'nullable|boolean',
            'fotokopi_kk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'fotokopi_ijazah' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'fotokopi_akte' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            'tato' => 'nullable|boolean',
            'tindik' => 'nullable|boolean',
            'buta_warna' => 'nullable|boolean',
            'tinggi_badan' => 'nullable|integer|min:100|max:250',
            'berat_badan' => 'nullable|integer|min:20|max:200',
            'hasil_tes' => 'nullable|string',

            'pas_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $lastRegistration = StudentRegistration::orderBy('id', 'desc')->first();

        if ($lastRegistration) {
            $lastNumber = (int) str_replace('REG-', '', $lastRegistration->no_pendaftaran);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $formattedNumber = 'REG-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        $validated['no_pendaftaran'] = $formattedNumber;

        if ($request->hasFile('fotokopi_kk')) {
            $path = $request->file('fotokopi_kk')->store('public/documents');
            $validated['fotokopi_kk'] = Storage::url($path);
        }
        if ($request->hasFile('fotokopi_ijazah')) {
            $path = $request->file('fotokopi_ijazah')->store('public/documents');
            $validated['fotokopi_ijazah'] = Storage::url($path);
        }
        if ($request->hasFile('fotokopi_akte')) {
            $path = $request->file('fotokopi_akte')->store('public/documents');
            $validated['fotokopi_akte'] = Storage::url($path);
        }
        if ($request->hasFile('pas_foto')) {
            $path = $request->file('pas_foto')->store('public/student_photos');
            $validated['pas_foto'] = Storage::url($path);
        }

        $booleanFields = [
            'foto_3x4',
            'fotokopi_kk',
            'fotokopi_ijazah',
            'fotokopi_akte',
            'tato',
            'tindik',
            'buta_warna',
            'penerima_pip'
        ];

        foreach ($booleanFields as $field) {
            if (!isset($validated[$field])) {
                $validated[$field] = false;
            }
        }

        $validated['user_id'] = Auth::id();

        StudentRegistration::create($validated);

        return redirect()->route('student-registrations.index')
            ->with('success', 'Pendaftaran berhasil disimpan!');
    }


    public function update(Request $request, StudentRegistration $studentRegistration)
    {
        $validated = $request->validate([
            'nama_pengantar' => 'nullable|string|max:255',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'golongan_darah' => 'nullable|string|max:10',
            'agama' => 'required|string|max:255',
            'pelajaran_agama' => 'nullable|string|max:255',
            'alamat' => 'required|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'jumlah_saudara' => 'nullable|integer|min:0',

            'sekolah_asal' => 'required|string|max:255',
            'nisn' => 'nullable|string|max:20',
            'no_sttb' => 'nullable|string|max:255',
            'tahun_sttb' => 'nullable|string|max:4',
            'alamat_sekolah' => 'nullable|string',
            'ijazah_terakhir' => 'nullable|string|max:255',
            'no_seri_ijazah' => 'nullable|string|max:255',
            'tahun_lulus' => 'nullable|digits:4',

            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'keadaan_ayah' => 'required|in:Masih Hidup,Sudah Meninggal',
            'keadaan_ibu' => 'required|in:Masih Hidup,Sudah Meninggal',
            'alamat_orang_tua' => 'nullable|string',
            'nama_wali' => 'nullable|string|max:255',
            'alamat_wali' => 'nullable|string',
            'telepon_wali' => 'nullable|string|max:20',
            'penerima_pip' => 'nullable|boolean',

            'jurusan' => 'required|in:Otomotif,DKV,Farmasi,Technopreneur',
            'mengetahui_dari' => 'nullable|string|max:255',

            'foto_3x4' => 'sometimes|boolean',
            'fotokopi_kk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'fotokopi_ijazah' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'fotokopi_akte' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            'tato' => 'nullable|boolean',
            'tindik' => 'nullable|boolean',
            'buta_warna' => 'nullable|boolean',
            'tinggi_badan' => 'nullable|integer|min:100|max:250',
            'berat_badan' => 'nullable|integer|min:20|max:200',
            'hasil_tes' => 'nullable|string',

            'pas_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $files = [
            'pas_foto' => 'student_photos',
            'fotokopi_kk' => 'student_documents',
            'fotokopi_ijazah' => 'student_documents',
            'fotokopi_akte' => 'student_documents',
        ];

        foreach ($files as $field => $folder) {
            if ($request->hasFile($field)) {
                if ($studentRegistration->$field) {
                    $oldFile = str_replace('/storage/', 'public/', $studentRegistration->$field);
                    Storage::delete($oldFile);
                }
                $path = $request->file($field)->store('public/' . $folder);
                $validated[$field] = Storage::url($path);
            }
        }

        $validated['user_id'] = $studentRegistration->user_id ?? Auth::id();

        $studentRegistration->update($validated);

        return redirect()->route('student-registrations.index')
            ->with('success', 'Data pendaftaran berhasil diperbarui!');
    }

    public function destroy(StudentRegistration $studentRegistration)
    {
        if ($studentRegistration->pas_foto) {
            $oldFile = str_replace('/storage', 'public', $studentRegistration->pas_foto);
            Storage::delete($oldFile);
        }

        $studentRegistration->delete();

        return redirect()->route('student-registrations.index')
            ->with('success', 'Data pendaftaran berhasil dihapus!');
    }
}
