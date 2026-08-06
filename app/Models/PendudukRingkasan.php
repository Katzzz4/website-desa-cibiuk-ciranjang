<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendudukRingkasan extends Model
{
    protected $table = 'penduduk_ringkasan';

    protected $fillable = [
        'tahun', 'total_kk', 'total_laki', 'total_perempuan',
        'lahir_laki', 'lahir_perempuan', 'mati_laki', 'mati_perempuan',
        'datang_laki', 'datang_perempuan', 'pergi_laki', 'pergi_perempuan',
    ];
}
