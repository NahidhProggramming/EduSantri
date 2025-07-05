<?php

namespace App\Providers;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Sekolah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        View::composer('*', function ($view) {
            if (Auth::check() && Auth::user()->hasRole('guru')) {
                $guru = Auth::user()->guru;

                $jadwals = $guru
                    ? Jadwal::with(['mataPelajaran', 'kelas', 'tahunAkademik'])
                    ->where('guru_id', $guru->id_guru)
                    ->orderBy('kelas_id')
                    ->get()
                    : collect();

                $view->with('jadwals', $jadwals);
            }
        });
    }
}
