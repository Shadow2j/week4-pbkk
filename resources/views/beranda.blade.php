{{--
    beranda.blade.php — Halaman Beranda (rute "/") (PRD §4.5).

    Menerima dari PageController beranda:
    - namaTamu (?string)   : dari query user=Nama (Challenge #2, sapaan dinamis)
    - namaProyek, tagline  : ringkasan proyek Agentic AI (DBGuardian).

    Challenge #2 aman saat parameter tidak ada: banner hanya render jika namaTamu
    terisi. Warna merek monokrom: hitam di mode terang, putih di mode gelap
    (tanpa warna ungu, tanpa gradien, tanpa emoji — ikon memakai SVG inline).
    Murni utility Tailwind.
--}}
@extends('layouts.app')

@section('title', 'Beranda — Profil Akademik')

@section('content')
    {{-- Sapaan dinamis (Challenge #2): /?user=Andi menyapa "Andi". --}}
    @if(!empty($namaTamu))
        <x-status-banner type="success" :message="'Selamat datang, ' . e($namaTamu) . '! Senang melihatmu di mini-website akademik ini.'" />
    @endif

    {{-- Hero sambutan --}}
    <section class="relative overflow-hidden rounded-3xl bg-slate-950 shadow-xl shadow-slate-950/20 ring-1 ring-slate-900/10 dark:bg-white dark:shadow-black/20 dark:ring-white/10">
        <div class="absolute -top-20 -right-20 h-64 w-64 rounded-full bg-white/10 blur-2xl dark:bg-slate-950/5" aria-hidden="true"></div>
        <div class="absolute -bottom-24 -left-16 h-64 w-64 rounded-full bg-white/10 blur-2xl dark:bg-slate-950/5" aria-hidden="true"></div>
        <div class="relative grid gap-8 p-7 sm:p-10 lg:grid-cols-[1.5fr_1fr] lg:items-center">
            <div>
                <p class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-[11px] font-bold tracking-widest text-slate-200 uppercase ring-1 ring-white/25 dark:bg-slate-950/5 dark:text-slate-600 dark:ring-slate-950/15">Mini-website akademik · Blade + Tailwind</p>
                <h1 class="mt-4 text-3xl font-black leading-tight text-white sm:text-4xl dark:text-slate-950">Halo, selamat datang di Profil Akademik</h1>
                <p class="mt-3 max-w-xl text-sm leading-relaxed text-slate-300 sm:text-base dark:text-slate-600">Satu master layout, dua komponen reusable, dan pipeline Vite lokal — menjelajahi profil diri, rancangan Agentic AI kelompok, dan formulir ide riset.</p>
                <div class="mt-6 flex flex-wrap gap-2.5">
                    <a href="{{ route('profil') }}" class="rounded-full bg-white px-5 py-2.5 text-sm font-bold text-slate-950 shadow transition hover:bg-slate-200 dark:bg-slate-950 dark:text-white dark:hover:bg-slate-800">Lihat Profil</a>
                    <a href="{{ route('ide-agent') }}" class="rounded-full px-5 py-2.5 text-sm font-bold text-white ring-1 ring-white/40 transition hover:bg-white/10 dark:text-slate-950 dark:ring-slate-950/30 dark:hover:bg-slate-950/5">Rancangan Ide-Riset</a>
                </div>
                <p class="mt-4 text-xs text-slate-400 dark:text-slate-500">Tips: coba /?user=Andi untuk sapaan dinamis, atau tombol bulan di navbar untuk tema gelap.</p>
            </div>
            <div class="grid gap-2.5">
                <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3 ring-1 ring-white/20 backdrop-blur dark:bg-slate-950/5 dark:ring-slate-950/15">
                    <span class="text-sm font-semibold text-white dark:text-slate-950">Blade Layout</span>
                    <span class="rounded-full bg-emerald-400/20 px-2.5 py-0.5 text-[11px] font-bold text-emerald-200 ring-1 ring-emerald-300/30 dark:bg-emerald-500/15 dark:text-emerald-700 dark:ring-emerald-600/30">DRY</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3 ring-1 ring-white/20 backdrop-blur dark:bg-slate-950/5 dark:ring-slate-950/15">
                    <span class="text-sm font-semibold text-white dark:text-slate-950">Komponen Reusable</span>
                    <span class="rounded-full bg-emerald-400/20 px-2.5 py-0.5 text-[11px] font-bold text-emerald-200 ring-1 ring-emerald-300/30 dark:bg-emerald-500/15 dark:text-emerald-700 dark:ring-emerald-600/30">2</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3 ring-1 ring-white/20 backdrop-blur dark:bg-slate-950/5 dark:ring-slate-950/15">
                    <span class="text-sm font-semibold text-white dark:text-slate-950">Vite + Tailwind NPM</span>
                    <span class="rounded-full bg-emerald-400/20 px-2.5 py-0.5 text-[11px] font-bold text-emerald-200 ring-1 ring-emerald-300/30 dark:bg-emerald-500/15 dark:text-emerald-700 dark:ring-emerald-600/30">TANPA CDN</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Ringkasan singkat proyek Agentic AI: database monitoring health (DBGuardian). --}}
    <section class="mt-8">
        <div class="flex flex-wrap items-end justify-between gap-2">
            <div>
                <p class="text-[11px] font-bold tracking-widest text-slate-500 uppercase dark:text-slate-400">Proyek kelompok</p>
                <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">{{ $namaProyek }} <span class="font-semibold text-slate-500 dark:text-slate-400">— {{ $tagline }}</span></h2>
            </div>
            <a href="{{ route('ide-agent') }}" class="text-sm font-bold text-slate-900 underline decoration-2 underline-offset-4 transition hover:opacity-70 dark:text-white">Detail rancangan</a>
        </div>
        <div class="mt-4 grid gap-4 md:grid-cols-3">
            <article class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:shadow-md dark:bg-slate-900 dark:ring-slate-800 dark:hover:shadow-black/40">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-rose-100 text-rose-600 dark:bg-rose-500/15 dark:text-rose-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                </span>
                <h3 class="mt-3 text-sm font-extrabold tracking-wide text-rose-600 uppercase dark:text-rose-400">Masalah</h3>
                <p class="mt-1.5 text-sm leading-relaxed text-slate-600 dark:text-slate-400">Gangguan database (slow query, deadlock, replication lag, pool penuh) sering terlambat terdeteksi hingga layanan akademik terdampak.</p>
            </article>
            <article class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:shadow-md dark:bg-slate-900 dark:ring-slate-800 dark:hover:shadow-black/40">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-slate-900 text-white dark:bg-white dark:text-slate-950">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25zm.75-12h9v9h-9z" /></svg>
                </span>
                <h3 class="mt-3 text-sm font-extrabold tracking-wide text-slate-900 uppercase dark:text-white">Solusi Agen</h3>
                <p class="mt-1.5 text-sm leading-relaxed text-slate-600 dark:text-slate-400">Empat agen otonom berkolaborasi: kolektor metrik, detektor anomali, analis akar masalah, dan remediasi aman terkendali.</p>
            </article>
            <article class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:shadow-md dark:bg-slate-900 dark:ring-slate-800 dark:hover:shadow-black/40">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </span>
                <h3 class="mt-3 text-sm font-extrabold tracking-wide text-emerald-600 uppercase dark:text-emerald-400">Manfaat</h3>
                <p class="mt-1.5 text-sm leading-relaxed text-slate-600 dark:text-slate-400">MTTR lebih singkat, runbook otomatis untuk DBA, dan dashboard kesehatan real-time untuk tiap layanan.</p>
            </article>
        </div>
    </section>
@endsection
