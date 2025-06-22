<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'siswa_id',
        'guru_id',
        'tanggal_kunjungan',
        'keterangan',
        'status'
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'datetime'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
