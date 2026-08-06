<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PetaLayer extends Model
{
    protected $table = 'peta_layer';

    protected $fillable = [
        'nama', 'keterangan', 'file_path', 'warna',
        'opasitas', 'tampil_di_pengaduan', 'aktif', 'urutan',
    ];

    protected $casts = [
        'tampil_di_pengaduan' => 'boolean',
        'aktif' => 'boolean',
    ];

    protected $appends = ['url_berkas', 'ukuran_terbaca'];

    public function getUrlBerkasAttribute(): ?string
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    public function getUkuranTerbacaAttribute(): ?string
    {
        try {
            if (!$this->file_path || !Storage::disk('public')->exists($this->file_path)) {
                return null;
            }
            $bytes = Storage::disk('public')->size($this->file_path);
        } catch (\Throwable $e) {
            return null;
        }

        if ($bytes < 1024) {
            return $bytes . ' B';
        }
        if ($bytes < 1048576) {
            return number_format($bytes / 1024, 0, ',', '.') . ' KB';
        }

        return number_format($bytes / 1048576, 1, ',', '.') . ' MB';
    }

    /** Data yang dikirim ke peta di halaman depan */
    public function untukPeta(): array
    {
        return [
            'nama'     => $this->nama,
            'url'      => $this->url_berkas,
            'warna'    => $this->warna,
            'opasitas' => $this->opasitas / 100,
        ];
    }

    public function scopeAktif($query)
    {
        return $query->where('aktif', true)->orderBy('urutan')->orderBy('nama');
    }
}