<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    protected $table = 'galeri';

    protected $fillable = ['judul', 'tipe', 'file_path', 'url_video'];

    protected $appends = ['id_youtube', 'url_embed', 'url_tonton', 'sampul_video'];

    /**
     * Mengambil kode video YouTube dari berbagai bentuk alamat yang biasa disalin:
     *   youtube.com/watch?v=XXX
     *   youtu.be/XXX
     *   youtube.com/embed/XXX
     *   youtube.com/shorts/XXX
     *   youtube.com/live/XXX
     */
    public function getIdYoutubeAttribute(): ?string
    {
        if (!$this->url_video) {
            return null;
        }

        $pola = '/(?:youtu\.be\/|youtube\.com\/(?:watch\?(?:.*&)?v=|embed\/|shorts\/|live\/))([a-zA-Z0-9_-]{6,})/';

        return preg_match($pola, $this->url_video, $m) ? $m[1] : null;
    }

    /** Alamat untuk disematkan di dalam halaman (iframe) */
    public function getUrlEmbedAttribute(): ?string
    {
        $id = $this->id_youtube;

        return $id ? "https://www.youtube.com/embed/{$id}?rel=0" : null;
    }

    /** Alamat untuk dibuka di tab baru — inilah yang boleh dipakai sebagai tautan biasa */
    public function getUrlTontonAttribute(): ?string
    {
        $id = $this->id_youtube;

        return $id ? "https://www.youtube.com/watch?v={$id}" : $this->url_video;
    }

    /** Gambar sampul video, diambil otomatis dari YouTube */
    public function getSampulVideoAttribute(): ?string
    {
        $id = $this->id_youtube;

        return $id ? "https://img.youtube.com/vi/{$id}/hqdefault.jpg" : null;
    }
}