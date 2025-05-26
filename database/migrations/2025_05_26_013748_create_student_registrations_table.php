<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_registrations', function (Blueprint $table) {
            $table->id();
            // A. IDENTITAS PRIBADI
            $table->string('no_pendaftaran')->unique();
            $table->string('nama_pengantar')->nullable();
            $table->string('nama');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('golongan_darah')->nullable();
            $table->string('agama');
            $table->string('pelajaran_agama')->nullable();
            $table->text('alamat');
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->unsignedTinyInteger('jumlah_saudara')->default(0);

            // B. PENDIDIKAN
            $table->string('sekolah_asal');
            $table->string('nisn')->nullable();
            $table->string('no_sttb')->nullable();
            $table->string('tahun_sttb')->nullable();
            $table->text('alamat_sekolah')->nullable();
            $table->string('ijazah_terakhir')->nullable();
            $table->string('no_seri_ijazah')->nullable();
            $table->year('tahun_lulus')->nullable();

            // C. ORANG TUA / WALI
            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->enum('keadaan_ayah', ['Masih Hidup', 'Sudah Meninggal']);
            $table->enum('keadaan_ibu', ['Masih Hidup', 'Sudah Meninggal']);
            $table->text('alamat_orang_tua')->nullable();
            $table->string('nama_wali')->nullable();
            $table->text('alamat_wali')->nullable();
            $table->string('telepon_wali')->nullable();
            $table->boolean('penerima_pip')->default(false);

            // D. JURUSAN / PEMINATAN
            $table->enum('jurusan', ['Otomotif', 'DKV', 'Farmasi', 'Technopreneur']);
            $table->string('mengetahui_dari')->nullable();

            // E. SYARAT-SYARAT
            $table->boolean('foto_3x4')->default(false);
            $table->boolean('fotokopi_kk')->default(false);
            $table->boolean('fotokopi_ijazah')->default(false);
            $table->boolean('fotokopi_akte')->default(false);

            // F. TES FISIK
            $table->boolean('tato')->default(false);
            $table->boolean('tindik')->default(false);
            $table->boolean('buta_warna')->default(false);
            $table->unsignedSmallInteger('tinggi_badan')->nullable();
            $table->unsignedSmallInteger('berat_badan')->nullable();
            $table->text('hasil_tes')->nullable();

            $table->string('pas_foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_registrations');
    }
};
