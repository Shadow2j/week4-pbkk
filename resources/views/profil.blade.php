{{--
    profil.blade.php — Halaman Profil (rute "/profil-mahasiswa") (PRD §4.5).

    Menerima profil (single source dari PageController profil) lalu
    menampilkannya via info-card. Detail tambahan (prodi, keahlian, minat)
    dilewatkan lewat slot agar komponen tetap generik/reusable.
    Varian gelap mengikuti class "dark" pada html. Murni utility Tailwind.
--}}
@extends('layouts.app')

@section('title', 'Profil Mahasiswa')

@section('content')
    <div class="mb-5 flex flex-wrap items-end justify-between gap-2">
        <div>
            <p class="text-[11px] font-bold tracking-widest text-slate-500 uppercase dark:text-slate-400">Tentang saya</p>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">Profil Mahasiswa</h1>
        </div>
        <a href="{{ route('ide-agent') }}" class="rounded-full bg-slate-900 px-4 py-2 text-xs font-bold text-white transition hover:bg-slate-700 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200">Lihat Ide-Riset →</a>
    </div>

    <x-info-card :nama="$profil['nama']" :nrp="$profil['nrp']" :foto="$profil['foto']" :deskripsi="$profil['deskripsi']">
        <div class="grid gap-6 md:grid-cols-2">
            <dl class="divide-y divide-slate-100 overflow-hidden rounded-2xl ring-1 ring-slate-200 dark:divide-slate-800 dark:ring-slate-700">
                <div class="flex items-center justify-between gap-3 bg-slate-50/60 px-4 py-3 dark:bg-white/5">
                    <dt class="text-xs font-semibold tracking-wide text-slate-500 uppercase dark:text-slate-400">Program Studi</dt>
                    <dd class="text-sm font-bold text-slate-900 dark:text-white">{{ $profil['prodi'] }}</dd>
                </div>
                <div class="flex items-center justify-between gap-3 px-4 py-3">
                    <dt class="text-xs font-semibold tracking-wide text-slate-500 uppercase dark:text-slate-400">Angkatan</dt>
                    <dd class="text-sm font-bold text-slate-900 dark:text-white">{{ $profil['angkatan'] }}</dd>
                </div>
                <div class="flex items-center justify-between gap-3 bg-slate-50/60 px-4 py-3 dark:bg-white/5">
                    <dt class="text-xs font-semibold tracking-wide text-slate-500 uppercase dark:text-slate-400">Fokus</dt>
                    <dd class="text-sm font-bold text-slate-900 dark:text-white">DBGuardian · Agentic AI</dd>
                </div>
            </dl>
            <div>
                <p class="text-xs font-bold tracking-widest text-slate-500 uppercase dark:text-slate-400">Keahlian</p>
                <p class="mt-2 flex flex-wrap gap-1.5">
                    @foreach($profil['keahlian'] as $skill)
                        <span class="rounded-full bg-slate-900 px-3 py-1 text-xs font-bold text-white dark:bg-white dark:text-slate-900">{{ $skill }}</span>
                    @endforeach
                </p>
                <p class="mt-4 text-xs font-bold tracking-widest text-slate-500 uppercase dark:text-slate-400">Minat Riset</p>
                <p class="mt-2 flex flex-wrap gap-1.5">
                    @foreach($profil['minat'] as $minat)
                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 ring-1 ring-emerald-200 dark:bg-emerald-500/15 dark:text-emerald-300 dark:ring-emerald-400/30">{{ $minat }}</span>
                    @endforeach
                </p>
            </div>
        </div>
    </x-info-card>
@endsection
