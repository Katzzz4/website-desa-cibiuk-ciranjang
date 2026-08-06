<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerangkatDesa extends Model
{
    protected $table = 'perangkat_desa';

    protected $fillable = [
        'nama', 'jabatan', 'atasan_jabatan', 'dusun_id', 'foto_path', 'urutan',
    ];

    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class);
    }
}
