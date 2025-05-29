<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Rekening;
use Illuminate\Http\Request;

class RekeningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rekenings = Rekening::latest()->get();
        return view('admin.rekening.index', compact('rekenings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:100',
            'nama_pemilik' => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:50',
            'keterangan' => 'nullable|string',
        ]);

        Rekening::create($request->all());

        return redirect()->route('rekenings.index')
            ->with('success', 'Rekening berhasil ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rekening $rekening)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:100',
            'nama_pemilik' => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:50',
            'keterangan' => 'nullable|string',
        ]);

        $rekening->update($request->all());

        return redirect()->route('rekenings.index')
            ->with('success', 'Rekening berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rekening $rekening)
    {
        $rekening->delete();

        return redirect()->route('rekenings.index')
            ->with('success', 'Rekening berhasil dihapus');
    }
}
