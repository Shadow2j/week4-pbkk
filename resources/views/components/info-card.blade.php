{{--
    components/info-card.blade.php — Kartu profil reusable (PRD §4.3).

    Props:
    - nama (string, wajib)  : nama mahasiswa
    - nrp (string, wajib)   : nomor registrasi pokok
    - foto (?string)        : path gambar lokal (public/); null = avatar inisial utility-only
    - deskripsi (string)    : deskripsi singkat
    Slot:
    - slot bawaan Blade: konten tambahan (prodi, keahlian, minat) agar komponen generik.

    Alasan reusable: markup kartu profil hanya ditulis sekali, dipakai di halaman
    profil. Warna merek monokrom: hitam di mode terang, putih di mode gelap.
    Tanpa CSS kustom — avatar dan dekorasi murni utility Tailwind termasuk
    varian gelapnya.
--}}
@props(['nama', 'nrp', 'foto' => null, 'deskripsi' => ''])

@php
    // Inisial untuk avatar fallback (mis. "Nama Mahasiswa" menjadi "NM").
    $inisial = collect(explode(' ', (string) $nama))->map(fn ($k) => mb_substr($k, 0, 1))->take(2)->join('');
@endphp

<article class="overflow-hidden rounded-3xl bg-white shadow-xl shadow-slate-200/60 ring-1 ring-slate-200 dark:bg-slate-900 dark:shadow-black/40 dark:ring-slate-800">
    {{-- Pita warna solid merek: hitam di terang, putih di gelap --}}
    <div class="h-24 bg-slate-950 dark:bg-white"></div>
    <div class="px-6 pb-6 sm:px-8">
        <div class="-mt-12 flex flex-col gap-4 sm:flex-row sm:items-end">
            @if($foto)
                <img src="{{ asset($foto) }}" alt="Foto {{ $nama }}" class="h-24 w-24 rounded-2xl object-cover shadow-lg ring-4 ring-white dark:ring-slate-900" width="96" height="96">
            @else
                {{-- Avatar inisial: murni utility Tailwind, tanpa gambar/CDN eksternal. --}}
                <div class="flex h-24 w-24 items-center justify-center rounded-2xl bg-slate-950 text-3xl font-black tracking-wide text-white shadow-lg ring-4 ring-white dark:bg-white dark:text-slate-950 dark:ring-slate-900" aria-hidden="true">{{ $inisial }}</div>
            @endif
            <div class="flex-1">
                <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">{{ $nama }}</h2>
                <p class="mt-1 flex flex-wrap items-center gap-2">
                    <span class="rounded-full bg-slate-900 px-3 py-1 text-[11px] font-bold tracking-widest text-white dark:bg-white dark:text-slate-900">NRP {{ $nrp }}</span>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold tracking-widest text-slate-700 ring-1 ring-slate-300 dark:bg-white/10 dark:text-slate-200 dark:ring-white/20">MAHASISWA ITS</span>
                </p>
            </div>
        </div>
        <p class="mt-4 text-sm leading-relaxed text-slate-600 dark:text-slate-400">{{ $deskripsi }}</p>
        @if(trim($slot) !== '')
            <hr class="my-5 border-slate-200 dark:border-slate-800">
            {{ $slot }}
        @endif
    </div>
</article>
