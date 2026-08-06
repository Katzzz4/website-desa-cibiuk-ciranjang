<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dusun extends Model
{
    protected $table = 'dusun';

    protected $fillable = ['nama', 'jarak_ke_desa_km'];

    public function laporan(): HasMany
    {
        return $this->hasMany(Laporan::class);
    }

    public function perangkat(): HasMany
    {
        return $this->hasMany(PerangkatDesa::class);
    }
}
