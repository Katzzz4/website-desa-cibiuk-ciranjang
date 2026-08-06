<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laporan extends Model
{
    protected $table = 'laporan';

    protected $fillable = [
        'no_tiket', 'kategori_laporan_id', 'dusun_id',
        'anonim', 'nama_pelapor', 'no_hp',
        'judul', 'deskripsi', 'latitude', 'longitude', 'alamat_lokasi',
        'tanggal_kejadian', 'status', 'alasan_tolak',
        'dokumentasi_selesai_path', 'selesai_at', 'ditangani_oleh',
    ];

    protected $casts = [
        'anonim' => 'boolean',
        'tanggal_kejadian' => 'date',
        'selesai_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Laporan $laporan) {
            if (empty($laporan->no_tiket)) {
                $laporan->no_tiket = static::generateNoTiket();
            }
        });
    }

    public static function generateNoTiket(): string
    {
        $tanggal = now()->format('Ymd');
        $prefix = "LPR-{$tanggal}-";

        $terakhir = static::where('no_tiket', 'like', "{$prefix}%")
            ->orderByDesc('id')
            ->first();

        $urutan = $terakhir
            ? ((int) substr($terakhir->no_tiket, -4)) + 1
            : 1;

        return $prefix . str_pad((string) $urutan, 4, '0', STR_PAD_LEFT);
    }

    public function scopeUntukPengguna($query, $pengguna)
    {
        if (!$pengguna || $pengguna->role !== 'kadus') {
            return $query;
        }

        if (!$pengguna->dusun_id) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('dusun_id', $pengguna->dusun_id);
    }

    /** Apakah pengguna ini berhak membuka/mengubah laporan tersebut */
    public function bolehDiaksesOleh($pengguna): bool
    {
        if (!$pengguna) {
            return false;
        }

        if ($pengguna->role !== 'kadus') {
            return true;
        }

        return $pengguna->dusun_id && $this->dusun_id === $pengguna->dusun_id;
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriLaporan::class, 'kategori_laporan_id');
    }

    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class);
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ditangani_oleh');
    }

    public function foto(): HasMany
    {
        return $this->hasMany(LaporanFoto::class);
    }

    public function tanggapan(): HasMany
    {
        return $this->hasMany(LaporanTanggapan::class)->latest();
    }
}