<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected
     $table = 'bukus';
    
    protected $fillable = ['judul', 'no_inventaris', 'no_klasifikasi' ,'pengarang','penerbit', 'tahun_terbit', 'edisi', 'isbn', 'kolase', 'jumlah', 'keterangan', 'kategori_id'];

    public function buku()
    {
        return $this->hasMany(Buku::class);
    }
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }
}
