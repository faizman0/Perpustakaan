<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookReturn extends Model
{
    use HasFactory;
    protected $fillable = ['borrowing_id', 'return_date', 'fine'];

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }
}
