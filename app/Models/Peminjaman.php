<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamen';

    protected $fillable = [
        'anggota_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'tanggal_kembali' => 'datetime',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class);
    }

    public function getStatusAttribute()
    {
        if ($this->pengembalian) {
            return '<span class="badge bg-success">Dikembalikan</span>';
        }

        $jatuhTempo = null;
        if ($this->tanggal_pinjam) {
            $jatuhTempo = \Carbon\Carbon::parse($this->tanggal_pinjam)->addDays(14);
            $now = \Carbon\Carbon::now();
            if ($now->gt($jatuhTempo)) {
                return '<span class="badge bg-danger">Terlambat</span>';
            }
        }
        
        return '<span class="badge bg-warning">Dipinjam</span>';
    }
}
