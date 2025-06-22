<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalians';
    protected $fillable = [
        'peminjaman_id',
        'tanggal_kembali',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_kembali' => 'date'
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }
}
