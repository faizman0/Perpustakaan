<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;
    protected $table = 'gurus';
    protected $fillable = ['nama', 'nip', 'jenis_kelamin'];

    public function kunjungan()
    {
        return $this->hasMany(Kunjungan::class);
    }
    
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function pengembalian()
    {
        return $this->hasMany(Pengembalian::class);
    }

    // Relasi ke Anggota
    public function anggota()
    {
        return $this->hasOne(Anggota::class);
    }
}
