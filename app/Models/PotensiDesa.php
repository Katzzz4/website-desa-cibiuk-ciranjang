<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PotensiDesa extends Model
{
    protected $table = 'potensi_desa';

    protected $fillable = ['jenis', 'nama', 'deskripsi', 'foto_path', 'kontak'];

    protected $appends = ['label_jenis', 'link_wa'];

    /** Daftar jenis potensi beserta labelnya, dipakai di form dan filter */
    public const JENIS = [
        'umkm' => 'UMKM',
        'pertanian' => 'Pertanian',
        'peternakan' => 'Peternakan',
        'kerajinan' => 'Kerajinan',
        'wisata' => 'Wisata',
    ];

    public function getLabelJenisAttribute(): string
    {
        return self::JENIS[$this->jenis] ?? ucfirst((string) $this->jenis);
    }

    /**
     * Ubah nomor HP jadi tautan WhatsApp.
     * Menangani format umum: 08xxx, 8xxx, +628xxx, 628xxx, dan
     * mengabaikan spasi/strip/tanda kurung yang sering ikut tertulis.
     */
    public function getLinkWaAttribute(): ?string
    {
        if (blank($this->kontak)) {
            return null;
        }

        $nomor = preg_replace('/\D/', '', $this->kontak);

        if (blank($nomor)) {
            return null;
        }

        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        } elseif (str_starts_with($nomor, '8')) {
            $nomor = '62' . $nomor;
        }

        return 'https://wa.me/' . $nomor;
    }
}