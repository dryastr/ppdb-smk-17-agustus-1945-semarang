<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_registration_id',
        'user_id',
        'rekening_id',
        'amount',
        'status',
        'metode_pembayaran',
        'bukti_pembayaran',
        'keterangan',
    ];

    public function registration()
    {
        return $this->belongsTo(StudentRegistration::class, 'student_registration_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studentRegistration()
    {
        return $this->belongsTo(StudentRegistration::class);
    }

    public function rekening()
    {
        return $this->belongsTo(Rekening::class);
    }

    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case 'menunggu':
                return 'Menunggu Konfirmasi';
            case 'dibayar':
                return 'Lunas';
            case 'gagal':
                return 'Gagal';
            case 'ditolak':
                return 'Ditolak';
            default:
                return ucfirst($this->status);
        }
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
}
