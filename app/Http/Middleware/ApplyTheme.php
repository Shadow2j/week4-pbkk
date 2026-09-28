<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * ApplyTheme — middleware tema terang/gelap untuk SELURUH halaman.
 *
 * Alasan desain:
 * - Dark mode ala website pada umumnya: sekali dipilih, berlaku di semua halaman
 *   dan persisten (session + cookie 90 hari), bukan hanya di satu halaman.
 * - Sumber nilai berprioritas: query ?mode=dark|light (termasuk challenge PRD §5
 *   di /ide-agent) → session → cookie → default 'light'. Nilai tak dikenal
 *   diabaikan secara aman (fallback light, layout tidak rusak).
 * - Hasilnya di-share ke semua view sebagai $mode/$isDark (single source of truth,
 *   DRY) sehingga layout cukup memasang class "dark" pada <html> dan seluruh
 *   styling gelap memakai varian Tailwind "dark:" (class-based via custom-variant).
 */
class ApplyTheme
{
    /**
     * Tentukan tema request ini, simpan pilihannya, dan bagikan ke semua view.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $query = $request->query('mode');

        if ($query === 'dark' || $query === 'light') {
            // Pilihan eksplisit via query (mis. ?mode=dark) selalu menang dan disimpan.
            $mode = $query;
            session(['theme' => $mode]);
        } else {
            // Tanpa query: pakai pilihan tersimpan, selain itu terang.
            $mode = session('theme', $request->cookie('theme', 'light'));
            if ($mode !== 'dark' && $mode !== 'light') {
                $mode = 'light';
            }
        }

        // Bagikan ke layout & semua view (dipakai untuk class "dark" dan status toggle).
        View::share(['mode' => $mode, 'isDark' => $mode === 'dark']);

        // Persistensi lintas kunjungan via cookie (dibaca saat session kosong).
        cookie()->queue('theme', $mode, 60 * 24 * 90);

        return $next($request);
    }
}
