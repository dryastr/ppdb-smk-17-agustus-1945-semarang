<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRegistration extends Model
{
    use HasFactory;

    protected $table = 'student_registrations';

    protected $fillable = [
        'user_id',
        'no_pendaftaran',
        'nama_pengantar',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'golongan_darah',
        'agama',
        'pelajaran_agama',
        'alamat',
        'telepon',
        'email',
        'jumlah_saudara',
        'sekolah_asal',
        'nisn',
        'no_sttb',
        'tahun_sttb',
        'alamat_sekolah',
        'ijazah_terakhir',
        'no_seri_ijazah',
        'tahun_lulus',
        'nama_ayah',
        'nama_ibu',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
        'keadaan_ayah',
        'keadaan_ibu',
        'alamat_orang_tua',
        'nama_wali',
        'alamat_wali',
        'telepon_wali',
        'penerima_pip',
        'jurusan',
        'mengetahui_dari',
        'foto_3x4',
        'fotokopi_kk',
        'fotokopi_ijazah',
        'fotokopi_akte',
        'tato',
        'tindik',
        'buta_warna',
        'tinggi_badan',
        'berat_badan',
        'hasil_tes',
        'pas_foto',
        'status',
        'keterangan_status',
        'keterangan_fisik_tato',
        'keterangan_fisik_tindik',
        'keterangan_fisik_butawarna',
        'keterangan_fisik_tinggi_berat',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'foto_3x4' => 'boolean',
        'tato' => 'boolean',
        'tindik' => 'boolean',
        'buta_warna' => 'boolean',
        'penerima_pip' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPasFotoUrlAttribute()
    {
        return $this->pas_foto ? asset('storage/student_photos/' . $this->pas_foto) : null;
    }

    public function getFotokopiKkUrlAttribute()
    {
        return $this->fotokopi_kk ? asset('storage/student_documents/' . $this->fotokopi_kk) : null;
    }

    public function getFotokopiIjazahUrlAttribute()
    {
        return $this->fotokopi_ijazah ? asset('storage/student_documents/' . $this->fotokopi_ijazah) : null;
    }

    public function getFotokopiAkteUrlAttribute()
    {
        return $this->fotokopi_akte ? asset('storage/student_documents/' . $this->fotokopi_akte) : null;
    }
}
