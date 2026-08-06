@php
    $namaDesa = $profil->nama_desa ?? 'Cibiuk';
    $statusLabel = [
        'menunggu' => 'Menunggu',
        'diproses' => 'Diproses',
        'selesai'  => 'Selesai',
        'ditolak'  => 'Ditolak',
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Pengaduan {{ $dari->translatedFormat('d M Y') }} – {{ $sampai->translatedFormat('d M Y') }}</title>
    <meta name="robots" content="noindex, nofollow">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root { --hijau:#0E5C3A; --ink:#171A18; --lembut:#5D635F; --garis:#D8DDD9; }

        * { box-sizing: border-box; }
        body {
            margin:0; padding:28px 20px;
            font-family:'Plus Jakarta Sans', sans-serif;
            background:#EDEFED; color:var(--ink); font-size:13px; line-height:1.55;
        }

        .lembar {
            width:210mm; min-height:297mm; margin:0 auto; padding:18mm 16mm;
            background:#fff; box-shadow:0 2px 20px rgba(0,0,0,.12);
        }

        /* ---------- Kop surat ---------- */
        .kop { display:flex; align-items:center; gap:16px; padding-bottom:12px; }
        .kop img { width:62px; height:62px; object-fit:contain; }
        .kop .teks { text-align:center; flex:1; }
        .kop .teks .baris1 { font-size:13px; letter-spacing:.04em; text-transform:uppercase; }
        .kop .teks .baris2 { font-size:19px; font-weight:700; letter-spacing:.02em; text-transform:uppercase; }
        .kop .teks .alamat { font-size:11px; color:var(--lembut); margin-top:3px; }
        .garis-kop { border:0; border-top:3px solid var(--ink); border-bottom:1px solid var(--ink);
                     height:4px; margin:0 0 20px; }

        /* ---------- Judul dokumen ---------- */
        .judul { text-align:center; margin-bottom:22px; }
        .judul h1 { font-size:15px; font-weight:700; text-transform:uppercase;
                    letter-spacing:.03em; margin:0; text-decoration:underline; }
        .judul p { font-size:12px; color:var(--lembut); margin:5px 0 0; }

        /* ---------- Bagian ---------- */
        h2.bagian { font-size:12px; font-weight:700; text-transform:uppercase;
                    letter-spacing:.06em; margin:22px 0 9px; color:var(--hijau); }

        table { width:100%; border-collapse:collapse; font-size:12px; }
        th, td { border:1px solid var(--garis); padding:6px 8px; text-align:left; vertical-align:top; }
        th { background:#F2F5F3; font-weight:600; font-size:11px; }
        td.angka, th.angka { text-align:center; width:52px; }
        tbody tr { page-break-inside:avoid; }

        .ringkas { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; }
        .ringkas .kotak { border:1px solid var(--garis); padding:10px 12px; }
        .ringkas .kotak .nilai { font-size:20px; font-weight:700; color:var(--hijau); }
        .ringkas .kotak .ket { font-size:10.5px; color:var(--lembut); margin-top:2px; }

        .catatan { font-size:11px; color:var(--lembut); margin-top:8px; font-style:italic; }

        /* ---------- Tanda tangan ---------- */
        .ttd { margin-top:34px; display:flex; justify-content:flex-end; page-break-inside:avoid; }
        .ttd .blok { width:64mm; text-align:center; font-size:12px; }
        .ttd .ruang { height:22mm; }
        .ttd .nama { font-weight:700; text-decoration:underline; }

        /* ---------- Panel pengaturan (tidak ikut tercetak) ---------- */
        .panel {
            width:210mm; margin:0 auto 16px; background:#fff; border:1px solid var(--garis);
            border-radius:12px; padding:14px 16px;
            display:flex; flex-wrap:wrap; align-items:end; gap:12px;
        }
        .panel label { display:block; font-size:11px; color:var(--lembut); margin-bottom:4px; }
        .panel input[type=date] {
            border:1px solid var(--garis); border-radius:8px; padding:7px 10px; font-size:13px;
            font-family:inherit;
        }
        .tombol {
            border:0; border-radius:8px; padding:9px 18px; font-size:13px; font-weight:600;
            background:var(--hijau); color:#fff; cursor:pointer; font-family:inherit;
            text-decoration:none; display:inline-flex; align-items:center; gap:7px;
        }
        .tombol-putih {
            background:#fff; color:var(--ink); border:1px solid var(--garis); font-weight:500;
        }
        .panel .petunjuk { font-size:11px; color:var(--lembut); width:100%; margin:2px 0 0; }

        /* ---------- Aturan saat dicetak ---------- */
        @media print {
            @page { size:A4; margin:0; }
            body { background:#fff; padding:0; font-size:11.5pt; }
            .lembar { width:auto; min-height:0; margin:0; padding:14mm 15mm; box-shadow:none; }
            .panel { display:none !important; }
            th { background:#EFEFEF !important; -webkit-print-color-adjust:exact; print-color-adjust:exact; }
            .ringkas .kotak .nilai { color:#000; }
            h2.bagian { color:#000; }
        }
    </style>
</head>
<body>

{{-- ============ PENGATURAN (hanya tampil di layar) ============ --}}
<form method="GET" class="panel">
    <div>
        <label for="dari">Dari Tanggal</label>
        <input type="date" id="dari" name="dari" value="{{ $dari->toDateString() }}">
    </div>
    <div>
        <label for="sampai">Sampai Tanggal</label>
        <input type="date" id="sampai" name="sampai" value="{{ $sampai->toDateString() }}">
    </div>

    <button type="submit" class="tombol tombol-putih">Terapkan</button>

    <button type="button" class="tombol" onclick="window.print()">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none"
             viewBox="0 0 24 24" stroke-width="1.9" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5z" />
        </svg>
        Cetak / Simpan PDF
    </button>

    <a href="{{ route('admin.laporan.index') }}" class="tombol tombol-putih">Kembali</a>

    <p class="petunjuk">
        Untuk menyimpan sebagai PDF: tekan tombol Cetak, lalu pada pilihan tujuan pilih
        <strong>Save as PDF</strong> atau <strong>Simpan sebagai PDF</strong>.
    </p>
</form>

{{-- ============ LEMBAR DOKUMEN ============ --}}
<div class="lembar">

    {{-- Kop surat --}}
    <div class="kop">
        @if($profil?->logo_path)
            <img src="{{ Storage::url($profil->logo_path) }}" alt="Logo Desa">
        @endif
        <div class="teks">
            <div class="baris1">Pemerintah Kabupaten {{ $profil->kabupaten ?? 'Cianjur' }}</div>
            <div class="baris1">Kecamatan {{ $profil->kecamatan ?? 'Ciranjang' }}</div>
            <div class="baris2">Desa {{ $namaDesa }}</div>
            @if($profil?->alamat_kantor)
                <div class="alamat">{{ $profil->alamat_kantor }}
                    @if($profil->telepon) &middot; Telp. {{ $profil->telepon }} @endif
                    @if($profil->email) &middot; {{ $profil->email }} @endif
                </div>
            @endif
        </div>
        @if($profil?->logo_path)
            <div style="width:62px;"></div>
        @endif
    </div>
    <hr class="garis-kop">

    {{-- Judul --}}
    <div class="judul">
        <h1>Rekapitulasi Laporan Pengaduan Masyarakat</h1>
        <p>
            Periode {{ $dari->translatedFormat('d F Y') }} sampai {{ $sampai->translatedFormat('d F Y') }}
        </p>
    </div>

    {{-- Ringkasan --}}
    <h2 class="bagian">A. Ringkasan</h2>
    <div class="ringkas">
        <div class="kotak">
            <div class="nilai">{{ number_format($total, 0, ',', '.') }}</div>
            <div class="ket">Laporan Masuk</div>
        </div>
        <div class="kotak">
            <div class="nilai">{{ number_format($perStatus['selesai'], 0, ',', '.') }}</div>
            <div class="ket">Selesai Ditangani</div>
        </div>
        <div class="kotak">
            <div class="nilai">{{ number_format($persenSelesai, 1, ',', '.') }}%</div>
            <div class="ket">Tingkat Penyelesaian</div>
        </div>
        <div class="kotak">
            <div class="nilai">{{ $rataRataHari !== null ? number_format($rataRataHari, 1, ',', '.') : '—' }}</div>
            <div class="ket">Rata-rata Hari Penanganan</div>
        </div>
    </div>

    <table style="margin-top:12px;">
        <thead>
            <tr>
                <th>Status Penanganan</th>
                <th class="angka">Jumlah</th>
                <th class="angka">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($statusLabel as $kunci => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td class="angka">{{ $perStatus[$kunci] }}</td>
                    <td class="angka">
                        {{ $total > 0 ? number_format($perStatus[$kunci] / $total * 100, 1, ',', '.') : '0,0' }}%
                    </td>
                </tr>
            @endforeach
            <tr style="font-weight:700; background:#F7F9F8;">
                <td>Jumlah</td>
                <td class="angka">{{ $total }}</td>
                <td class="angka">{{ $total > 0 ? '100,0%' : '0,0%' }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Per kategori --}}
    <h2 class="bagian">B. Rincian Menurut Jenis Laporan</h2>
    @if($perKategori->count())
        <table>
            <thead>
                <tr>
                    <th class="angka">No</th>
                    <th>Jenis Laporan</th>
                    <th class="angka">Masuk</th>
                    <th class="angka">Selesai</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($perKategori as $k)
                    <tr>
                        <td class="angka">{{ $loop->iteration }}</td>
                        <td>{{ $k['nama'] }}</td>
                        <td class="angka">{{ $k['jumlah'] }}</td>
                        <td class="angka">{{ $k['selesai'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="catatan">Tidak ada data pada periode ini.</p>
    @endif

    {{-- Per dusun --}}
    <h2 class="bagian">C. Rincian Menurut Dusun</h2>
    @if($perDusun->count())
        <table>
            <thead>
                <tr>
                    <th class="angka">No</th>
                    <th>Dusun</th>
                    <th class="angka">Masuk</th>
                    <th class="angka">Selesai</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($perDusun as $d)
                    <tr>
                        <td class="angka">{{ $loop->iteration }}</td>
                        <td>{{ $d['nama'] }}</td>
                        <td class="angka">{{ $d['jumlah'] }}</td>
                        <td class="angka">{{ $d['selesai'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="catatan">Tidak ada data pada periode ini.</p>
    @endif

    {{-- Daftar laporan --}}
    <h2 class="bagian">D. Daftar Laporan</h2>
    @if($laporan->count())
        <table>
            <thead>
                <tr>
                    <th class="angka">No</th>
                    <th style="width:26mm;">No. Tiket</th>
                    <th style="width:18mm;">Tanggal</th>
                    <th>Uraian Laporan</th>
                    <th style="width:22mm;">Dusun</th>
                    <th style="width:20mm;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporan as $l)
                    <tr>
                        <td class="angka">{{ $loop->iteration }}</td>
                        <td style="font-size:10.5px;">{{ $l->no_tiket }}</td>
                        <td>{{ $l->created_at->format('d/m/y') }}</td>
                        <td>
                            {{ $l->judul }}
                            <div style="font-size:10.5px; color:var(--lembut); margin-top:2px;">
                                {{ $l->kategori->nama ?? '-' }}
                                @if($l->status === 'selesai' && $l->selesai_at)
                                    &middot; selesai dalam {{ $l->created_at->diffInDays($l->selesai_at) }} hari
                                @endif
                            </div>
                        </td>
                        <td>{{ $l->dusun->nama ?? '-' }}</td>
                        <td>{{ $statusLabel[$l->status] ?? $l->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p class="catatan">
            Identitas pelapor tidak dicantumkan dalam rekap ini demi menjaga kerahasiaan data warga.
        </p>
    @else
        <p class="catatan">Tidak ada laporan yang masuk pada periode ini.</p>
    @endif

    {{-- Tanda tangan --}}
    <div class="ttd">
        <div class="blok">
            <div>{{ $profil->nama_desa ?? 'Cibiuk' }}, {{ now()->translatedFormat('d F Y') }}</div>
            <div>Kepala Desa {{ $namaDesa }}</div>
            <div class="ruang"></div>
            <div class="nama">{{ $profil->nama_kepala_desa ?? '.................................' }}</div>
        </div>
    </div>
</div>

</body>
</html>