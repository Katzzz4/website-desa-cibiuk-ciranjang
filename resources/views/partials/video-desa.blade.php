{{--
    VIDEO PENGENALAN DESA

    Video baru dimuat setelah pengunjung menekan tombol putar. Sebelum itu
    yang tampil hanya gambar sampul, sehingga halaman tidak mengunduh skrip
    YouTube untuk pengunjung yang tidak menonton — penting bagi warga yang
    membuka lewat data seluler.

    Membutuhkan variabel $profil. Pemakaian:
        @include('partials.video-desa')
--}}

@if($profil?->id_video)
    <section class="border-t" style="border-color: var(--garis);">
        <div class="wadah py-11">

            <div class="reveal mb-6">
                <p class="label-bagian">Mengenal Lebih Dekat</p>
                <h2 class="font-display text-2xl font-bold mt-1.5">
                    {{ $profil->video_profil_judul ?: 'Video Pengenalan Desa ' . ($profil->nama_desa ?? 'Cibiuk') }}
                </h2>
                @if($profil->video_profil_keterangan)
                    <p class="text-sm mt-2.5 max-w-2xl leading-relaxed" style="color: var(--lembut);">
                        {{ $profil->video_profil_keterangan }}
                    </p>
                @endif
            </div>

            <div class="reveal-skala kartu overflow-hidden">
                <div id="wadah-video-desa" class="relative w-full bg-black" style="padding-top: 56.25%;">

                    {{-- Sampul: tampil sebelum video dimuat --}}
                    <button type="button" id="putar-video-desa"
                            class="absolute inset-0 w-full h-full group"
                            aria-label="Putar video pengenalan desa">

                        <img src="{{ $profil->video_sampul }}"
                             alt="Sampul video pengenalan Desa {{ $profil->nama_desa ?? 'Cibiuk' }}"
                             class="absolute inset-0 w-full h-full object-cover transition duration-500 group-hover:scale-105"
                             loading="lazy"
                             onerror="this.src='https://img.youtube.com/vi/{{ $profil->id_video }}/hqdefault.jpg'">

                        <span class="absolute inset-0"
                              style="background: linear-gradient(180deg, rgba(7,46,29,.18) 0%, rgba(7,46,29,.42) 100%);"></span>

                        {{-- tombol putar --}}
                        <span class="absolute inset-0 flex items-center justify-center">
                            <span class="w-20 h-20 rounded-full flex items-center justify-center shadow-xl transition group-hover:scale-110"
                                  style="background: rgba(255,255,255,.95);">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                     class="w-8 h-8 ml-1" style="color: var(--sawah-dark);">
                                    <path d="M8 5v14l11-7z" />
                                </svg>
                            </span>
                        </span>

                        <span class="absolute left-0 right-0 bottom-0 p-5 text-left">
                            <span class="text-xs text-white/80 font-medium tracking-wide">
                                Tekan untuk memutar
                            </span>
                        </span>
                    </button>
                </div>
            </div>

            <p class="text-xs mt-3" style="color: var(--lembut);">
                Video tidak dapat diputar?
                <a href="{{ $profil->video_tonton }}" target="_blank" rel="noopener"
                   class="hover:underline underline-offset-4" style="color: var(--padi);">
                    Tonton di YouTube
                </a>
            </p>
        </div>
    </section>

    <script>
        (function () {
            const tombol = document.getElementById('putar-video-desa');
            if (!tombol) return;

            tombol.addEventListener('click', function () {
                const wadah = document.getElementById('wadah-video-desa');

                const bingkai = document.createElement('iframe');
                bingkai.src = @json($profil->video_embed);
                bingkai.title = 'Video pengenalan Desa {{ $profil->nama_desa ?? "Cibiuk" }}';
                bingkai.className = 'absolute inset-0 w-full h-full';
                bingkai.setAttribute('frameborder', '0');
                bingkai.setAttribute('allowfullscreen', '');
                bingkai.setAttribute('allow',
                    'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');

                wadah.innerHTML = '';
                wadah.appendChild(bingkai);
            });
        })();
    </script>
@endif