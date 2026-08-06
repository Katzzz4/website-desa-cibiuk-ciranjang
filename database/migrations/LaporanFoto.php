<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanFoto extends Model
{
    protected $table = 'laporan_foto';

    protected $fillable = ['laporan_id', 'file_path'];

    public function laporan(): BelongsTo
    {
        return $this->belongsTo(Laporan::class);
    }
}
