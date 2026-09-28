{{--
    ide-agent.blade.php — Halaman Ide-Riset (rute "/ide-agent") (PRD §4.5).

    Menerima dari PageController ideAgent:
    - mode ('light'/'dark'), isDark (bool): dipakai untuk status tombol
      Light/Dark; tema halaman sendiri mengikuti class "dark" pada html
      (middleware ApplyTheme) sehingga query mode=dark mengubah background
      Tailwind halaman (Challenge #1) sekaligus berlaku situs-wide.
    - agen (array)      : 4 agen DBGuardian (Collector, Detector, Analyst, Remediation).
    - metrik (array)    : contoh metrik kesehatan untuk progress bar.
    - kategoriIde       : opsi select formulir (sama dengan validasi controller).

    Hasil submit (flash session 'status') ditampilkan via status-banner.
    Warna merek monokrom: hitam di mode terang, putih di mode gelap (tanpa
    warna ungu, tanpa gradien, tanpa emoji). Murni utility Tailwind (PRD §7
    revisi): semua varian warna ditulis sebagai string literal utuh agar
    terdeteksi scanner Tailwind.
--}}
@extends('layouts.app')

@section('title', 'Ide-Riset Agentic AI — DBGuardian')

@section('content')
@php
    // Pemetaan palet agen ke utility literal (jangan di-interpolasi dinamis).
    // Entri "slate" mengikuti warna merek (hitam di terang, putih di gelap).
    $gayaAgen = [
        'slate' => ['lencana' => 'bg-slate-950 text-white dark:bg-white dark:text-slate-950', 'kartu' => 'bg-slate-100 text-slate-800 ring-slate-300 dark:bg-white/10 dark:text-slate-200 dark:ring-white/20', 'bilah' => 'bg-slate-900 dark:bg-white'],
        'amber' => ['lencana' => 'bg-amber-500 text-white', 'kartu' => 'bg-amber-50 text-amber-700 ring-amber-200 dark:bg-amber-500/10 dark:text-amber-200 dark:ring-amber-400/30', 'bilah' => 'bg-amber-400'],
        'sky' => ['lencana' => 'bg-sky-500 text-white', 'kartu' => 'bg-sky-50 text-sky-700 ring-sky-200 dark:bg-sky-500/10 dark:text-sky-200 dark:ring-sky-400/30', 'bilah' => 'bg-sky-500'],
        'emerald' => ['lencana' => 'bg-emerald-500 text-white', 'kartu' => 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-200 dark:ring-emerald-400/30', 'bilah' => 'bg-emerald-500'],
    ];
    // Kelas input form yang berulang (DRY) — literal utuh + varian error + varian gelap.
    $input = 'w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-slate-900 focus:ring-2 focus:ring-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-white dark:focus:ring-white/20';
    $inputError = 'w-full rounded-xl border border-rose-400 bg-rose-50 px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-500 focus:ring-2 focus:ring-rose-200 dark:border-rose-500 dark:bg-rose-500/10 dark:text-slate-100';
@endphp

    {{-- Kepala halaman + penanda mode (Challenge #1 ikut ke sini via query mode=dark). --}}
    <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
        <div>
            <p class="text-[11px] font-bold tracking-widest text-slate-500 uppercase dark:text-slate-400">Rancangan platform · Database monitoring health</p>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">DBGuardian <span class="font-semibold text-slate-400 dark:text-slate-500">/ Ide-Riset</span></h1>
        </div>
        <div class="flex items-center gap-1 rounded-full bg-slate-950 p-1 text-xs font-bold dark:bg-white dark:text-slate-900" role="group" aria-label="Mode tampilan">
            <a href="{{ route('ide-agent', ['mode' => 'light']) }}" class="flex items-center gap-1.5 rounded-full px-4 py-1.5 transition {{ $isDark ? 'text-slate-400 hover:text-white dark:text-slate-500 dark:hover:text-slate-900' : 'bg-white text-slate-900 shadow dark:bg-slate-900 dark:text-white' }}">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" /></svg>
                Light
            </a>
            <a href="{{ route('ide-agent', ['mode' => 'dark']) }}" class="flex items-center gap-1.5 rounded-full px-4 py-1.5 transition {{ $isDark ? 'bg-white text-slate-900 shadow dark:bg-slate-900 dark:text-white' : 'text-slate-400 hover:text-white dark:text-slate-500 dark:hover:text-slate-900' }}">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0012 21.75a9.753 9.753 0 009.752-6.748z" /></svg>
                Dark
            </a>
        </div>
    </div>

    {{-- Hasil submit form (flash dari storeIde) — via komponen reusable. --}}
    @if(session('status'))
        <x-status-banner :type="session('status')['type']" :message="session('status')['message']" />
    @endif
    @if($errors->any())
        <x-status-banner type="danger" message="Formulir belum valid — periksa kembali isian bertanda merah." />
    @endif

    {{-- Panel arsitektur: terang/gelap via varian "dark:" (class pada html). --}}
    <section class="mb-6 overflow-hidden rounded-3xl border border-slate-200 bg-white text-slate-800 shadow-xl shadow-slate-200/50 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 dark:shadow-black/40">
        <div class="bg-slate-950 px-6 py-5 dark:bg-white">
            <h2 class="text-lg font-extrabold text-white dark:text-slate-950">Arsitektur Multi-Agen</h2>
            <p class="mt-0.5 text-xs text-slate-300 dark:text-slate-600">Aplikasi dan DBMS ke Collector, Detector, Analyst, Remediation, lalu dashboard dan notifikasi DBA</p>
        </div>
        <div class="grid gap-3 p-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($agen as $a)
                @php($g = $gayaAgen[$a['warna']] ?? $gayaAgen['slate'])
                <article class="rounded-2xl p-4 ring-1 transition hover:shadow-md {{ $g['kartu'] }}">
                    <p class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg text-sm font-black {{ $g['lencana'] }}">{{ $loop->iteration }}</span>
                        <span class="text-sm font-extrabold">{{ $a['nama'] }}</span>
                    </p>
                    <p class="mt-2 text-xs leading-relaxed opacity-80">{{ $a['peran'] }}</p>
                </article>
            @endforeach
        </div>

        <div class="grid gap-6 border-t border-slate-200 px-6 py-6 dark:border-slate-800 lg:grid-cols-2">
            <div>
                <h3 class="text-sm font-extrabold tracking-wide uppercase">Alur Kerja Agen</h3>
                <ol class="mt-3 space-y-2.5">
                    @foreach(['Collector menarik metrik dan log tiap 15 detik.', 'Detector menandai anomali vs baseline 7 hari.', 'Analyst mengorelasikan EXPLAIN, lock-wait, dan tren.', 'Remediation mengeksekusi aksi aman / eskalasi runbook.'] as $i => $langkah)
                        <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-950 text-[11px] font-black text-white dark:bg-white dark:text-slate-950">{{ $i + 1 }}</span>
                            <span>{{ $langkah }}</span>
                        </li>
                    @endforeach
                </ol>
            </div>
            <div>
                <h3 class="text-sm font-extrabold tracking-wide uppercase">Contoh Metrik Kesehatan</h3>
                <div class="mt-3 space-y-3.5">
                    @foreach($metrik as $m)
                        @php($g = $gayaAgen[$m['warna']] ?? $gayaAgen['emerald'])
                        <div>
                            <div class="mb-1 flex items-center justify-between text-xs">
                                <span class="font-bold">{{ $m['label'] }}</span>
                                <span class="text-slate-500 dark:text-slate-400">{{ $m['keterangan'] }}</span>
                            </div>
                            <div class="h-2.5 overflow-hidden rounded-full bg-slate-200 dark:bg-white/10" role="progressbar" aria-label="{{ $m['label'] }}" aria-valuenow="{{ $m['nilai'] }}" aria-valuemin="0" aria-valuemax="100">
                                <div class="h-full rounded-full {{ $g['bilah'] }}" style="width: {{ $m['nilai'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Formulir pengumpulan ide riset --}}
    <section class="overflow-hidden rounded-3xl bg-white shadow-xl shadow-slate-200/60 ring-1 ring-slate-200 dark:bg-slate-900 dark:shadow-black/40 dark:ring-slate-800">
        <div class="bg-slate-950 px-6 py-4 dark:bg-white">
            <h2 class="text-sm font-extrabold tracking-widest text-white uppercase dark:text-slate-950">Formulir Pengumpulan Ide</h2>
            <p class="mt-0.5 text-xs text-slate-400 dark:text-slate-600">Respons tampil lewat status-banner setelah submit.</p>
        </div>
        <form method="POST" action="{{ route('ide-agent.store', ['mode' => $mode]) }}" class="grid gap-4 p-6 sm:grid-cols-2">
            @csrf
            <div>
                <label for="nama" class="mb-1.5 block text-xs font-bold tracking-widest text-slate-600 uppercase dark:text-slate-300">Nama Pengusul</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Nama Anda" required class="@error('nama') {{ $inputError }} @else {{ $input }} @enderror">
                @error('nama')<p class="mt-1 text-xs font-semibold text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="kategori" class="mb-1.5 block text-xs font-bold tracking-widest text-slate-600 uppercase dark:text-slate-300">Kategori Ide</label>
                <select id="kategori" name="kategori" required class="@error('kategori') {{ $inputError }} @else {{ $input }} @enderror">
                    <option value="">— Pilih kategori —</option>
                    @foreach($kategoriIde as $k)
                        <option value="{{ $k }}" @selected(old('kategori') === $k)>{{ $k }}</option>
                    @endforeach
                </select>
                @error('kategori')<p class="mt-1 text-xs font-semibold text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label for="judul" class="mb-1.5 block text-xs font-bold tracking-widest text-slate-600 uppercase dark:text-slate-300">Judul Ide</label>
                <input type="text" id="judul" name="judul" value="{{ old('judul') }}" placeholder="cth. Prediksi replication lag dengan LSTM" required class="@error('judul') {{ $inputError }} @else {{ $input }} @enderror">
                @error('judul')<p class="mt-1 text-xs font-semibold text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label for="deskripsi" class="mb-1.5 block text-xs font-bold tracking-widest text-slate-600 uppercase dark:text-slate-300">Deskripsi (min. 20 karakter)</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Jelaskan masalah database yang disasar, data yang dipakai, dan aksi agen yang diusulkan..." required class="@error('deskripsi') {{ $inputError }} @else {{ $input }} @enderror">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')<p class="mt-1 text-xs font-semibold text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
            </div>
            <div class="flex flex-wrap gap-2.5 sm:col-span-2">
                <button type="submit" class="rounded-full bg-slate-950 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-slate-950/25 transition hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:shadow-black/30 dark:hover:bg-slate-200">Kirim Ide</button>
                <button type="reset" class="rounded-full px-6 py-2.5 text-sm font-bold text-slate-600 ring-1 ring-slate-300 transition hover:bg-slate-50 dark:text-slate-300 dark:ring-slate-700 dark:hover:bg-white/5">Reset</button>
            </div>
        </form>
    </section>
@endsection
