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
}
