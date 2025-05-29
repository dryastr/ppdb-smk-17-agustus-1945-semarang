<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\RegistrationFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegistrationFlowsController extends Controller
{
    public function index()
    {
        $flows = RegistrationFlow::orderBy('step_number')->get();

        return view('admin.registration-flows.index', compact('flows'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'step_number' => 'required|integer|min:1|unique:registration_flows,step_number',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal menambah Alur Pendaftaran. Periksa kembali input Anda.');
        }

        try {
            RegistrationFlow::create($request->all());

            return redirect()->back()->with('success', 'Alur Pendaftaran berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambah Alur Pendaftaran: ' . $e->getMessage());
        }
    }

    public function update(Request $request, RegistrationFlow $registration_flow)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'step_number' => 'required|integer|min:1|unique:registration_flows,step_number',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal memperbarui Alur Pendaftaran. Periksa kembali input Anda.');
        }

        try {
            $registration_flow->update($request->all());

            return redirect()->back()->with('success', 'Alur Pendaftaran berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui Alur Pendaftaran: ' . $e->getMessage());
        }
    }

    public function destroy(RegistrationFlow $registration_flow)
    {
        try {
            $registration_flow->delete();

            return redirect()->back()->with('success', 'Alur Pendaftaran berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus Alur Pendaftaran: ' . $e->getMessage());
        }
    }
}
