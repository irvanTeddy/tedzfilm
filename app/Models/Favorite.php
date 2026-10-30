<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    // Tambahkan atribut yang diizinkan untuk diisi secara mass-assignment
    protected $fillable = [
        'movie_id',
        'title',
        'poster_path',
    ];
}
