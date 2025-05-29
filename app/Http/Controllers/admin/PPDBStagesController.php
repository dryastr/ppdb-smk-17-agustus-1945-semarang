<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PpdbStage; // Import model PpdbStage
use Illuminate\Support\Str; // Untuk membuat slug

class PPDBStagesController extends Controller
{
    public function index()
    {
        $stages = PpdbStage::orderBy('order')->get();

        return view('admin.ppdb-stages.index', compact('stages'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validatedData['slug'] = Str::slug($validatedData['name']);
        $validatedData['is_active'] = $request->has('is_active');

        PpdbStage::create($validatedData);

        return redirect()->route('ppdb-stages.index')->with('success', 'Tahap PPDB berhasil ditambahkan!');
    }

    public function update(Request $request, PpdbStage $ppdbStage)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validatedData['slug'] = Str::slug($validatedData['name']);
        $validatedData['is_active'] = $request->has('is_active');

        $ppdbStage->update($validatedData);

        return redirect()->route('ppdb-stages.index')->with('success', 'Tahap PPDB berhasil diperbarui!');
    }

    public function destroy(PpdbStage $ppdbStage)
    {
        $ppdbStage->delete();

        return redirect()->route('ppdb-stages.index')->with('success', 'Tahap PPDB berhasil dihapus!');
    }
}
