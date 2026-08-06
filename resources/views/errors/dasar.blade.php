{{--
    Kerangka halaman error.
    Sengaja TIDAK mengambil data apa pun dari database — bila penyebab errornya
    justru koneksi database, halaman ini harus tetap bisa tampil.
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('kode') — Desa Cibiuk</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --hijau:#0E5C3A; --hijau-tua:#093F28; --aksen:#157F4F; --kertas:#F7F8F7; --ink:#171A18; --lembut:#5D635F; --garis:#E3E7E4; }
        * { box-sizing: border-box; }
        body {
            margin:0; min-height:100vh; display:flex; align-items:center; justify-content:center;
            padding:24px; font-family:'Plus Jakarta Sans',sans-serif;
            background:var(--kertas); color:var(--ink);
        }
        .kotak { width:100%; max-width:440px; text-align:center; }
        .lencana {
            display:inline-flex; align-items:center; gap:10px; margin-bottom:28px;
            text-decoration:none; color:var(--ink);
        }
        .lencana span.bulat {
            width:38px; height:38px; border-radius:999px; background:var(--hijau); color:#fff;
            display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px;
        }
        .lencana span.nama { font-weight:700; font-size:15px; letter-spacing:-0.01em; }
        .kartu { background:#fff; border:1px solid var(--garis); border-radius:16px; padding:36px 28px; }
        .kode { font-size:52px; font-weight:700; line-height:1; letter-spacing:-0.03em; color:var(--hijau); }
        h1 { font-size:19px; font-weight:700; margin:14px 0 8px; letter-spacing:-0.02em; }
        p { font-size:14px; line-height:1.65; color:var(--lembut); margin:0; }
        .tombol {
            display:inline-flex; align-items:center; gap:8px; margin-top:24px;
            padding:11px 22px; border-radius:10px; background:var(--hijau); color:#fff;
            text-decoration:none; font-size:14px; font-weight:600;
        }
        .tombol:hover { background:var(--hijau-tua); }
        .tombol-kedua {
            display:inline-flex; align-items:center; gap:8px; margin-top:24px;
            padding:11px 22px; border-radius:10px; background:#fff; color:var(--ink);
            border:1px solid var(--garis); text-decoration:none; font-size:14px; font-weight:500;
        }
        .tombol-kedua:hover { background:var(--kertas); }
        .deret-tombol { display:flex; flex-wrap:wrap; gap:10px; justify-content:center; }
        .bantuan { margin-top:20px; font-size:12px; color:var(--lembut); }
        .bantuan a { color:var(--aksen); }
    </style>
</head>
<body>
    <div class="kotak">
        <a href="{{ url('/') }}" class="lencana">
            <span class="bulat">DC</span>
            <span class="nama">Desa Cibiuk</span>
        </a>

        <div class="kartu">
            <div class="kode">@yield('kode')</div>
            <h1>@yield('judul')</h1>
            <p>@yield('pesan')</p>

            @hasSection('aksi')
                @yield('aksi')
            @else
                <a href="{{ url('/') }}" class="tombol">Kembali ke Beranda</a>
            @endif
        </div>

        <p class="bantuan">
            Butuh bantuan? <a href="{{ url('/pengaduan') }}">Sampaikan pengaduan</a>
            atau hubungi kantor Desa Cibiuk.
        </p>
    </div>
</body>
</html>