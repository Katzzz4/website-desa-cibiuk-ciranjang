<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendudukData extends Model
{
    protected $table = 'penduduk_data';

    protected $fillable = [
        'penduduk_kategori_id',
        'dusun_id',
        'label',
        'jumlah_laki',
        'jumlah_perempuan',
        'tahun',
    ];

    protected $appends = ['jumlah_total'];

    public function getJumlahTotalAttribute(): int
    {
        return $this->jumlah_laki + $this->jumlah_perempuan;
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(PendudukKategori::class, 'penduduk_kategori_id');
    }

    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class);
    }
}
