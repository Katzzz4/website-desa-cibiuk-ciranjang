<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriLaporan extends Model
{
    protected $table = 'kategori_laporan';

    protected $fillable = ['nama', 'icon'];

    public function laporan(): HasMany
    {
        return $this->hasMany(Laporan::class, 'kategori_laporan_id');
    }
}
