<?php

/**
 * routes/web.php — Definisi rute mini-website akademik (PRD §4.2).
 *
 * Keputusan desain:
 * - Satu PageController menangani ketiga halaman (single controller, multiple methods).
 * - Nama rute (beranda, profil, ide-agent) dipakai layout untuk status aktif navbar (DRY).
 * - POST /ide-agent hanya untuk validasi form + flash session; tanpa database (PRD §8).
 */

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'beranda'])->name('beranda');
Route::get('/profil-mahasiswa', [PageController::class, 'profil'])->name('profil');
Route::get('/ide-agent', [PageController::class, 'ideAgent'])->name('ide-agent');
Route::post('/ide-agent', [PageController::class, 'storeIde'])->name('ide-agent.store');
// Toggle tema situs (dark/light): tetap satu controller, menyimpan ke session+cookie.
Route::get('/tema/{mode}', [PageController::class, 'tema'])->whereIn('mode', ['dark', 'light'])->name('tema');
