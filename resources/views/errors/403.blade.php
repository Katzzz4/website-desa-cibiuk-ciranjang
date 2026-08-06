@extends('errors.dasar')

@section('kode', '403')
@section('judul', 'Akses Ditolak')
@section('pesan', 'Anda tidak memiliki izin untuk membuka halaman ini. Bila menurut Anda ini keliru, hubungi Super Admin desa.')

@section('aksi')
    <div class="deret-tombol">
        @auth
            {{-- masih masuk: beri jalan kembali ke halaman yang boleh dibuka --}}
            <a href="{{ url('/admin/dashboard') }}" class="tombol">Kembali ke Dashboard</a>

            {{-- sekaligus jalan keluar bila ingin berganti akun --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="tombol-kedua" style="cursor:pointer;">Keluar &amp; Ganti Akun</button>
            </form>
        @else
            <a href="{{ url('/') }}" class="tombol">Kembali ke Beranda</a>
            <a href="{{ route('login') }}" class="tombol-kedua">Masuk</a>
        @endauth
    </div>
@endsection