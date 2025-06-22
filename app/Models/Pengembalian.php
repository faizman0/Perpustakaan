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
        'tanggal_kembali'
    ];

    protected $casts = [
        'tanggal_kembali' => 'date'
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function getStatusDetailAttribute()
    {
        $tanggalKembali = \Carbon\Carbon::parse($this->tanggal_kembali);
        $tanggalPinjam = \Carbon\Carbon::parse($this->peminjaman->tanggal_pinjam);
        $jatuhTempo = $tanggalPinjam->addDays(14);
        $terlambat = $tanggalKembali->diffInDays($jatuhTempo, false);

        $status = '<span class="badge bg-success">Dikembalikan</span>';
        if ($terlambat < 0) {
            $status .= '<br><small class="text-danger">(Terlambat ' . abs($terlambat) . ' hari)</small>';
        }

        return $status;
    }
}
