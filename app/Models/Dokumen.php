<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Dokumen extends Model
{
    protected $table = 'dokumen';

    protected $fillable = ['nama', 'klasifikasi', 'kategori', 'file_path'];

    protected $appends = ['ekstensi', 'ukuran_terbaca', 'label_kategori', 'label_klasifikasi'];

    /**
     * Pengelompokan utama yang dipilih warga di halaman awal.
     * Tiap klasifikasi memiliki daftar jenis dokumennya sendiri.
     */
    public const KLASIFIKASI = [
        'produk_hukum' => [
            'label' => 'Produk Hukum',
            'ket'   => 'Peraturan Desa, Peraturan Kepala Desa, dan Surat Keputusan yang berlaku di Desa Cibiuk.',
            'jenis' => [
                'perdes'   => 'Peraturan Desa',
                'perkades' => 'Peraturan Kepala Desa',
                'sk'       => 'Surat Keputusan',
                'lainnya'  => 'Lainnya',
            ],
        ],
        'surat_menyurat' => [
            'label' => 'Surat Menyurat',
            'ket'   => 'Contoh dan format surat yang dapat diunduh warga untuk keperluan administrasi.',
            'jenis' => [
                'format_surat'   => 'Format Surat',
                'permohonan'     => 'Surat Permohonan',
                'keterangan'     => 'Surat Keterangan',
                'pengantar'      => 'Surat Pengantar',
                'lainnya'        => 'Lainnya',
            ],
        ],
    ];

    /** Daftar jenis dokumen untuk klasifikasi tertentu */
    public static function jenisUntuk(string $klasifikasi): array
    {
        return self::KLASIFIKASI[$klasifikasi]['jenis'] ?? [];
    }

    public function getLabelKlasifikasiAttribute(): string
    {
        return self::KLASIFIKASI[$this->klasifikasi]['label'] ?? 'Lainnya';
    }

    public function getLabelKategoriAttribute(): string
    {
        return self::jenisUntuk($this->klasifikasi)[$this->kategori]
            ?? ucfirst(str_replace('_', ' ', (string) $this->kategori));
    }

    /** Ekstensi berkas, misalnya pdf atau docx */
    public function getEkstensiAttribute(): string
    {
        return strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION)) ?: 'file';
    }

    /** Ukuran berkas dalam bentuk yang mudah dibaca */
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
}