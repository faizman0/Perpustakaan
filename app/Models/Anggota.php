<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggotas';
    
    protected $fillable = [
        'kode_anggota',
        'siswa_id',
        'guru_id',
        'status'
    ];

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Relasi ke Guru
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    // Relasi ke Kunjungan
    public function kunjungans()
    {
        return $this->hasMany(Kunjungan::class);
    }

    // Relasi ke Peminjaman
    public function peminjamen()
    {
        return $this->hasMany(Peminjaman::class);
    }

    // Accessor untuk mendapatkan nama anggota
    public function getNamaAttribute()
    {
        if ($this->siswa_id) {
            return $this->siswa->nama;
        }
        return $this->guru->nama;
    }

    // Accessor untuk mendapatkan tipe anggota
    public function getTipeAttribute()
    {
        return $this->siswa_id ? 'Siswa' : 'Guru';
    }

    // Method untuk generate kode anggota otomatis
    public static function generateKodeAnggota()
    {
        $lastAnggota = self::orderBy('id', 'desc')->first();
        
        if (!$lastAnggota) {
            return 'A1';
        }
        
        $lastNumber = (int) substr($lastAnggota->kode_anggota, 1);
        return 'A' . ($lastNumber + 1);
    }
} 