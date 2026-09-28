<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * PageController — satu controller untuk tiga halaman statis (PRD §4.2).
 *
 * Alasan desain:
 * - Single controller, multiple methods agar routing ramping dan konsisten.
 * - Data berulang (profil mahasiswa, arsitektur agen, metrik) dipusatkan di sini
 *   sebagai single source of truth (prinsip DRY, PRD §7) lalu di-pass ke view.
 * - Tidak ada query database (out of scope, PRD §8); form ide hanya divalidasi
 *   lalu dikembalikan sebagai flash session untuk ditampilkan via <x-status-banner>.
 */
class PageController extends Controller
{
    /**
     * Sumber tunggal data profil mahasiswa (DRY).
     *
     * Foto dikosongkan (null) sehingga <x-info-card> otomatis menampilkan
     * avatar inisial berbasis CSS lokal — tanpa CDN/gambar eksternal.
     *
     * @return array{nama:string,nrp:string,prodi:string,angkatan:string,foto:?string,deskripsi:string,keahlian:array,minat:array}
     */
    private function profilData(): array
    {
        return [
            'nama' => 'Raymond Julius Pardosi',
            'nrp' => '5025241268',
            'prodi' => 'Teknik Informatika ITS',
            'angkatan' => '2024',
            'foto' => null,
            'deskripsi' => 'Mahasiswa Teknik Informatika ITS yang tertarik pada rekayasa perangkat lunak dan Agentic AI untuk observabilitas database.',
            'keahlian' => ['Laravel & Blade', 'SQL & Administrasi DB', 'Vite & Tailwind'],
            'minat' => ['Database Reliability', 'Agentic AI', 'Observability'],
        ];
    }

    /**
     * Definisi agen-agen pada platform monitoring (single source untuk view ide-agent).
     *
     * Kunci 'warna' memakai nama palet Tailwind (slate/amber/sky/emerald) yang
     * dipetakan ke string utility literal di view — tidak pernah di-interpolasi
     * (mis. "bg-{$warna}-100") agar terdeteksi scanner Tailwind (PRD §7).
     *
     * @return array<int, array{nama:string,peran:string,warna:string}>
     */
    private function arsitekturAgen(): array
    {
        return [
            ['nama' => 'Collector Agent', 'peran' => 'Menarik metrik tiap 15 detik: CPU, memori, slow query, replication lag, connection pool.', 'warna' => 'slate'],
            ['nama' => 'Anomaly Detector', 'peran' => 'Mendeteksi anomali dengan threshold adaptif + perbandingan baseline 7 hari.', 'warna' => 'amber'],
            ['nama' => 'Root-Cause Analyst', 'peran' => 'Mengorelasikan log, EXPLAIN plan, dan lock-wait untuk menyimpulkan akar masalah.', 'warna' => 'sky'],
            ['nama' => 'Auto-Remediation', 'peran' => 'Aksi aman terkendali: kill slow query, scale pool, atau eskalasi ke DBA dengan runbook.', 'warna' => 'emerald'],
        ];
    }

    /**
     * Contoh metrik kesehatan database untuk visualisasi progress bar.
     *
     * @return array<int, array{label:string,nilai:int,warna:string,keterangan:string}>
     */
    private function metrikKesehatan(): array
    {
        return [
            ['label' => 'CPU Database', 'nilai' => 42, 'warna' => 'emerald', 'keterangan' => 'Normal (< 70%)'],
            ['label' => 'Connection Pool', 'nilai' => 68, 'warna' => 'amber', 'keterangan' => '68/100 koneksi'],
            ['label' => 'Slow Query (>1s)', 'nilai' => 15, 'warna' => 'sky', 'keterangan' => '15 query/jam'],
            ['label' => 'Replication Lag', 'nilai' => 8, 'warna' => 'emerald', 'keterangan' => '0.8 detik'],
        ];
    }

    /**
     * GET / — Beranda.
     * Challenge #2: query ?user=Nama memicu banner sapaan dinamis.
     */
    public function beranda(Request $request)
    {
        // Trim agar "?user=" kosong dianggap tidak ada (tidak merusak layout).
        $namaTamu = trim((string) $request->query('user', ''));
        $namaTamu = $namaTamu === '' ? null : $namaTamu;

        return view('beranda', [
            'namaTamu' => $namaTamu,
            'namaProyek' => 'DBGuardian',
            'tagline' => 'Agentic AI untuk Monitoring Kesehatan Database',
        ]);
    }

    /**
     * GET /profil-mahasiswa — Profil diri via <x-info-card>.
     */
    public function profil()
    {
        return view('profil', [
            'profil' => $this->profilData(),
        ]);
    }

    /**
     * GET /ide-agent — Visualisasi rancangan + formulir ide.
     * Challenge #1: query ?mode=dark mengubah tema via variabel Blade.
     *
     * Nilai $mode diambil dari middleware ApplyTheme (sudah menggabungkan query,
     * session, dan cookie) agar konsisten dengan tema situs di semua halaman.
     */
    public function ideAgent(Request $request)
    {
        // Single source of truth tema ada di middleware; controller hanya membaca.
        $mode = view()->shared('mode', 'light');
        $mode = $mode === 'dark' ? 'dark' : 'light';

        return view('ide-agent', [
            'mode' => $mode,
            'isDark' => $mode === 'dark',
            'agen' => $this->arsitekturAgen(),
            'metrik' => $this->metrikKesehatan(),
            'kategoriIde' => ['Monitoring', 'Deteksi Anomali', 'Optimasi Query', 'Keamanan & Backup'],
        ]);
    }

    /**
     * GET /tema/{mode} — Toggle tema situs (dipakai tombol di navbar).
     *
     * Menyimpan pilihan ke session + cookie lalu kembali ke halaman asal,
     * sehingga dark mode berlaku di SEMUA halaman seperti website pada umumnya.
     * Route dibatasi whereIn dark|light; nilai lain tak akan sampai ke sini.
     */
    public function tema(string $mode)
    {
        $mode = $mode === 'dark' ? 'dark' : 'light';
        session(['theme' => $mode]);

        // back() memakai header Referer; fallback beranda bila header absen.
        return redirect()->back(fallback: route('beranda'))
            ->withCookie(cookie('theme', $mode, 60 * 24 * 90));
    }

    /**
     * POST /ide-agent — Terima formulir ide riset, validasi, lalu redirect
     * dengan flash session agar <x-status-banner> menampilkan hasilnya.
     */
    public function storeIde(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'judul' => 'required|string|max:150',
            'kategori' => 'required|in:Monitoring,Deteksi Anomali,Optimasi Query,Keamanan & Backup',
            'deskripsi' => 'required|string|min:20|max:2000',
        ]);

        return redirect()
            ->route('ide-agent', ['mode' => $request->query('mode')])
            ->with('status', [
                'type' => 'success',
                'message' => 'Terima kasih, ' . $validated['nama'] . '! Ide "' . $validated['judul'] . '" (' . $validated['kategori'] . ') berhasil dicatat untuk DBGuardian.',
            ])
            ->withInput();
    }
}
