<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswas';
    protected $fillable = ['nama', 'nis', 'jenis_kelamin', 'kelas_id'];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class)->onDelete('cascade');
    }

    public function kunjungan()
    {
        return $this->hasMany(Kunjungan::class)->onDelete('cascade');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
