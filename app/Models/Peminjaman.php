<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamen';

    protected $fillable = [
        'siswa_id',
        'guru_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'tanggal_kembali' => 'datetime',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class);
    }
}
