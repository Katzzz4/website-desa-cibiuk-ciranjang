<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilDesa extends Model
{
    protected $table = 'profil_desa';

    protected $fillable = [
        'nama_desa', 'kecamatan', 'kabupaten', 'provinsi',
        'sejarah', 'visi', 'misi', 'luas_wilayah_ha',
        'batas_utara', 'batas_selatan', 'batas_timur', 'batas_barat',
        'jarak_ke_kabupaten_km', 'jarak_ke_kecamatan_km',
        'nama_kepala_desa', 'peta_wilayah_path', 'logo_path',
        'alamat_kantor', 'telepon', 'email', 'jam_pelayanan', 'foto_hero_path',
        'latitude', 'longitude', 'zoom_peta',
        'video_profil_url', 'video_profil_judul', 'video_profil_keterangan',
    ];

    protected $casts = [
        'misi' => 'array', // otomatis di-decode dari JSON jadi array poin misi
    ];

    protected $appends = [
        'wilayah_lengkap', 'baris_jam_pelayanan',
        'id_video', 'video_embed', 'video_tonton', 'video_sampul',
    ];

    /** Dipakai bila koordinat desa belum diisi dari dashboard */
    public const KOORDINAT_CADANGAN = ['lat' => -6.812528, 'lng' => 107.260071, 'zoom' => 15];

    /** Titik tengah peta: koordinat desa bila ada, jika tidak pakai cadangan */
    public function getTitikPetaAttribute(): array
    {
        return [
            'lat'  => $this->latitude !== null ? (float) $this->latitude : self::KOORDINAT_CADANGAN['lat'],
            'lng'  => $this->longitude !== null ? (float) $this->longitude : self::KOORDINAT_CADANGAN['lng'],
            'zoom' => (int) ($this->zoom_peta ?: self::KOORDINAT_CADANGAN['zoom']),
        ];
    }

    /** Contoh hasil: "Kecamatan Ciranjang, Kabupaten Cianjur, Jawa Barat" */
    public function getWilayahLengkapAttribute(): string
    {
        $bagian = array_filter([
            $this->kecamatan ? 'Kecamatan ' . $this->kecamatan : null,
            $this->kabupaten ? 'Kabupaten ' . $this->kabupaten : null,
            $this->provinsi,
        ]);

        return implode(', ', $bagian);
    }

    /** Jam pelayanan disimpan satu baris per hari, dipecah jadi array untuk footer */
    public function getBarisJamPelayananAttribute(): array
    {
        if (blank($this->jam_pelayanan)) {
            return [];
        }

        return collect(preg_split('/\r\n|\r|\n/', $this->jam_pelayanan))
            ->map(fn ($baris) => trim($baris))
            ->filter()
            ->values()
            ->all();
    }

    /** Nomor telepon desa dalam bentuk tautan WhatsApp, jika diisi */
    public function getLinkWaAttribute(): ?string
    {
        if (blank($this->telepon)) {
            return null;
        }

        $nomor = preg_replace('/\D/', '', $this->telepon);

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

    // ================================================================
    // VIDEO PENGENALAN DESA
    // ================================================================

    /**
     * Mengambil kode video YouTube dari berbagai bentuk alamat yang biasa
     * disalin perangkat desa: watch?v=, youtu.be, embed, shorts, dan live.
     */
    public function getIdVideoAttribute(): ?string
    {
        if (blank($this->video_profil_url)) {
            return null;
        }

        $pola = '/(?:youtu\.be\/|youtube\.com\/(?:watch\?(?:.*&)?v=|embed\/|shorts\/|live\/))([a-zA-Z0-9_-]{6,})/';

        return preg_match($pola, $this->video_profil_url, $m) ? $m[1] : null;
    }

    /** Alamat untuk disematkan di dalam halaman */
    public function getVideoEmbedAttribute(): ?string
    {
        $id = $this->id_video;

        return $id ? "https://www.youtube.com/embed/{$id}?rel=0&autoplay=1" : null;
    }

    /** Alamat untuk dibuka di tab baru bila penyematan ditolak pemilik video */
    public function getVideoTontonAttribute(): ?string
    {
        $id = $this->id_video;

        return $id ? "https://www.youtube.com/watch?v={$id}" : $this->video_profil_url;
    }

    /** Gambar sampul video, diambil otomatis dari YouTube */
    public function getVideoSampulAttribute(): ?string
    {
        $id = $this->id_video;

        return $id ? "https://img.youtube.com/vi/{$id}/maxresdefault.jpg" : null;
    }
}