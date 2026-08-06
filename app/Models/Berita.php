<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Berita extends Model
{
    protected $table = 'berita';

    protected $fillable = [
        'user_id', 'judul', 'slug', 'konten', 'thumbnail_path', 'kategori', 'tanggal_publish',
    ];

    protected $casts = [
        'tanggal_publish' => 'datetime',
    ];

    public function penulis(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
