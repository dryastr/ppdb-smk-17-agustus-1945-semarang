<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HeroSectionController extends Controller
{
    /**
     * Menampilkan dan mengelola satu-satunya data Hero Section.
     */
    public function index()
    {
        // Ambil data hero section pertama, atau buat instance baru jika belum ada
        $heroSection = HeroSection::firstOrNew([]);

        return view('admin.hero-section.index', compact('heroSection'));
    }

    /**
     * Menyimpan atau memperbarui data Hero Section.
     */
    public function storeOrUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link_persyaratan' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal menyimpan Hero Section. Periksa kembali input Anda.');
        }

        try {
            // Ambil data hero section pertama atau buat baru jika tidak ada
            $heroSection = HeroSection::firstOrNew([]);

            // Handle image upload jika ada
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($heroSection->image && Storage::exists('public/' . $heroSection->image)) {
                    Storage::delete('public/' . $heroSection->image);
                }

                // Simpan gambar baru
                $imagePath = $request->file('image')->store('hero_images', 'public');
                $heroSection->image = $imagePath;
            }

            // Update atau setel nilai-nilai lainnya
            $heroSection->title = $request->title;
            $heroSection->description = $request->description;
            $heroSection->link_persyaratan = $request->link_persyaratan;

            $heroSection->save(); // Simpan perubahan atau buat data baru

            return redirect()->back()->with('success', 'Hero Section berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan Hero Section: ' . $e->getMessage());
        }
    }
}
