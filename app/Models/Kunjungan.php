<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'anggota_id',
        'tanggal_kunjungan',
        'keterangan',
        'status'
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'datetime'
    ];

    // Relasi ke Anggota
    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    // Relasi ke Siswa (melalui anggota)
    public function siswa()
    {
        return $this->hasOneThrough(Siswa::class, Anggota::class, 'id', 'id', 'anggota_id', 'siswa_id');
    }

    // Relasi ke Guru (melalui anggota)
    public function guru()
    {
        return $this->hasOneThrough(Guru::class, Anggota::class, 'id', 'id', 'anggota_id', 'guru_id');
    }

    // Accessor untuk mendapatkan nama peminjam
    public function getNamaPeminjamAttribute()
    {
        return $this->anggota->nama;
    }

    // Accessor untuk mendapatkan tipe peminjam
    public function getTipePeminjamAttribute()
    {
        return $this->anggota->tipe;
    }
}
