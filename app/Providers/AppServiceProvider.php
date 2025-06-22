<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Siswa;
use App\Models\Guru;
use App\Observers\SiswaObserver;
use App\Observers\GuruObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Peminjaman;
use Carbon\Carbon;

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
        // Siswa::observe(SiswaObserver::class);
        // Guru::observe(GuruObserver::class);

        View::composer('layouts.main', function ($view) {
            if (Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('petugas'))) {
                $peminjamanTerlambat = Peminjaman::whereDoesntHave('pengembalian')
                    ->where('tanggal_pinjam', '<', Carbon::now()->subDays(14)->toDateTimeString())
                    ->get();
                $view->with('peminjamanTerlambat', $peminjamanTerlambat);
            } else {
                $view->with('peminjamanTerlambat', collect());
            }
        });
    }
}
