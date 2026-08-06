<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PendudukKategori extends Model
{
    protected $table = 'penduduk_kategori';

    protected $fillable = ['nama', 'slug', 'urutan'];

    public function data(): HasMany
    {
        return $this->hasMany(PendudukData::class, 'penduduk_kategori_id');
    }
}
