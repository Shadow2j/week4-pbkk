{{--
    layouts/app.blade.php — Master layout tunggal (PRD §4.1).

    Berisi SATU-SATUNYA struktur HTML (head, navbar, container, footer) agar
    halaman anak tidak menduplikasi markup (DRY). Halaman anak wajib
    extends layouts.app + section title/content.

    Tema situs (revisi): dark mode berlaku SEMUA halaman seperti website pada
    umumnya. Middleware ApplyTheme membagikan variabel mode/isDark ke layout;
    layout memasang class "dark" pada html sehingga utility bervarian "dark:"
    aktif. Toggle di navbar menyimpan pilihan (session + cookie). Warna merek
    monokrom: hitam di mode terang, putih di mode gelap (tanpa warna ungu,
    tanpa gradien, tanpa emoji — ikon memakai SVG inline). CSS/JS via
    vite bundle lokal, tanpa link CDN. Class berulang dikelompokkan di variabel
    blok php di bawah (DRY, PRD §7) dan ditulis utuh agar terbaca scanner Tailwind.
--}}
@php
    // Kelas tautan navbar — ditulis utuh (literal) agar tidak ter-purge oleh Tailwind.
    $navBase = 'rounded-full px-4 py-2 text-sm font-semibold transition duration-200';
    $navAktif = 'bg-white text-slate-950 shadow-sm dark:bg-slate-950 dark:text-white';
    $navBiasa = 'text-slate-300 hover:bg-white/15 hover:text-white dark:text-slate-600 dark:hover:bg-slate-950/5 dark:hover:text-slate-950';
@endphp
<!doctype html>
<html lang="id"@if($isDark ?? false) class="dark"@endif>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Profil Akademik')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-slate-100 font-sans text-slate-800 antialiased dark:bg-slate-950 dark:text-slate-200">

    {{-- Navbar statis: satu-satunya definisi navigasi (DRY). Status aktif via nama rute. --}}
    <header class="sticky top-0 z-50 bg-slate-950 shadow-lg shadow-slate-950/20 dark:bg-white dark:shadow-black/10">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-3 px-4 py-3 sm:px-6">
            <a href="{{ route('beranda') }}" class="flex items-center gap-2.5">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/15 text-sm font-black tracking-tight text-white ring-1 ring-white/30 dark:bg-slate-950/10 dark:text-slate-950 dark:ring-slate-950/20">DB</span>
                <span class="leading-tight">
                    <span class="block text-sm font-extrabold tracking-wide text-white dark:text-slate-950">PROFIL AKADEMIK</span>
                    <span class="block text-[11px] font-medium tracking-widest text-slate-400 uppercase dark:text-slate-500">PBKK · Tugas 4</span>
                </span>
            </a>
            <nav class="flex flex-wrap items-center gap-1.5" aria-label="Navigasi utama">
                <a href="{{ route('beranda') }}" class="{{ $navBase }} {{ request()->routeIs('beranda') ? $navAktif : $navBiasa }}">Beranda</a>
                <a href="{{ route('profil') }}" class="{{ $navBase }} {{ request()->routeIs('profil') ? $navAktif : $navBiasa }}">Profil</a>
                <a href="{{ route('ide-agent') }}" class="{{ $navBase }} {{ request()->routeIs('ide-agent*') ? $navAktif : $navBiasa }}">Ide-Riset</a>
                {{-- Toggle tema situs: gelap untuk semua halaman, tersimpan di session + cookie. --}}
                @if($isDark ?? false)
                    <a href="{{ route('tema', ['mode' => 'light']) }}" title="Kembali ke mode terang" aria-label="Kembali ke mode terang"
                       class="rounded-full bg-slate-950/5 p-2.5 text-slate-950 ring-1 ring-slate-950/20 transition hover:bg-slate-950/10">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" /></svg>
                    </a>
                @else
                    <a href="{{ route('tema', ['mode' => 'dark']) }}" title="Aktifkan mode gelap" aria-label="Aktifkan mode gelap"
                       class="rounded-full bg-white/10 p-2.5 text-white ring-1 ring-white/25 transition hover:bg-white/20">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0012 21.75a9.753 9.753 0 009.752-6.748z" /></svg>
                    </a>
                @endif
            </nav>
        </div>
    </header>

    {{-- Container konten: diisi halaman anak lewat section content. --}}
    <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-8 sm:px-6">
        @yield('content')
    </main>

    {{-- Footer ITS: satu-satunya definisi footer. --}}
    <footer class="mt-auto border-t border-slate-200 bg-white dark:border-slate-800 dark:bg-black">
        <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-2 px-4 py-5 text-center sm:px-6 md:flex-row md:text-left">
            <p class="text-xs text-slate-500 dark:text-slate-400">Institut Teknologi Sepuluh Nopember (ITS) — Tugas 4 PBKK · CPMK-1 · Blade + Tailwind + Vite</p>
            <p class="flex items-center gap-2">
                <span class="rounded-full bg-slate-500/10 px-3 py-1 text-[11px] font-bold tracking-wide text-slate-600 ring-1 ring-slate-500/25 dark:text-slate-300">LARAVEL BLADE</span>
                <span class="rounded-full bg-emerald-500/10 px-3 py-1 text-[11px] font-bold tracking-wide text-emerald-600 ring-1 ring-emerald-500/25 dark:text-emerald-300">TANPA CDN</span>
            </p>
        </div>
    </footer>

    {{-- Interaksi kecil tanpa CDN: tombol data-tutup menutup banner (dipakai status-banner). --}}
    <script>
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('[data-tutup]');
            if (!btn) return;
            const banner = btn.closest('[data-banner]');
            if (banner) banner.remove();
        });
    </script>

</body>
</html>
