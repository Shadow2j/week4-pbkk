{{--
    components/status-banner.blade.php — Banner notifikasi reusable (PRD §4.3).

    Props:
    - type (string)    : success|danger|warning|info|dark — dipetakan ke utility Tailwind.
    - message (string) : teks utama. Jika kosong, isi diambil dari slot.
    Slot:
    - slot bawaan Blade: dipakai saat pesan butuh markup tambahan.

    DRY (PRD §7): tiap varian ditulis sebagai string literal utuh di tabel varian
    (termasuk pasangan varian "dark:") agar terdeteksi scanner Tailwind — tidak ada
    konstruksi dinamis nama warna. Dipakai di: beranda (sapaan user), profil
    (catatan), ide-agent (hasil submit form).
--}}
@props(['type' => 'info', 'message' => ''])

@php
    $varian = [
        'success' => ['kotak' => 'border-emerald-200 bg-emerald-50 text-emerald-900 dark:border-emerald-400/30 dark:bg-emerald-500/10 dark:text-emerald-200', 'ikon' => 'bg-emerald-500'],
        'danger' => ['kotak' => 'border-rose-200 bg-rose-50 text-rose-900 dark:border-rose-400/30 dark:bg-rose-500/10 dark:text-rose-200', 'ikon' => 'bg-rose-500'],
        'warning' => ['kotak' => 'border-amber-200 bg-amber-50 text-amber-900 dark:border-amber-400/30 dark:bg-amber-500/10 dark:text-amber-200', 'ikon' => 'bg-amber-500'],
        'info' => ['kotak' => 'border-sky-200 bg-sky-50 text-sky-900 dark:border-sky-400/30 dark:bg-sky-500/10 dark:text-sky-200', 'ikon' => 'bg-sky-500'],
        'dark' => ['kotak' => 'border-slate-700 bg-slate-900 text-slate-100 dark:border-slate-600 dark:bg-black dark:text-slate-200', 'ikon' => 'bg-slate-400'],
        'primary' => ['kotak' => 'border-slate-900 bg-slate-950 text-white dark:border-white dark:bg-white dark:text-slate-950', 'ikon' => 'bg-slate-400'],
        'secondary' => ['kotak' => 'border-slate-200 bg-slate-50 text-slate-800 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200', 'ikon' => 'bg-slate-400'],
    ];
    $gaya = $varian[$type] ?? $varian['info'];
@endphp

@if($message !== '' || trim($slot) !== '')
    <div data-banner class="mb-5 flex items-start gap-3 rounded-2xl border px-4 py-3 shadow-sm {{ $gaya['kotak'] }}" role="alert">
        <span class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full {{ $gaya['ikon'] }}" aria-hidden="true"></span>
        <div class="flex-1 text-sm leading-relaxed">
            @if($message !== '')
                {{ $message }}
            @endif
            {{ $slot }}
        </div>
        <button type="button" data-tutup aria-label="Tutup notifikasi" class="rounded-lg px-2 py-0.5 text-lg leading-none opacity-60 transition hover:opacity-100">×</button>
    </div>
@endif
