<?php

namespace App\Observers;

use App\Models\Guru;
use App\Models\Anggota;

class GuruObserver
{
    /**
     * Handle the Guru "created" event.
     */
    public function created(Guru $guru): void
    {
        // Dihapus: auto-generate anggota untuk guru baru
    }

    /**
     * Handle the Guru "updated" event.
     */
    public function updated(Guru $guru): void
    {
        //
    }

    /**
     * Handle the Guru "deleted" event.
     */
    public function deleted(Guru $guru): void
    {
        // Hapus anggota ketika guru dihapus
        $guru->anggota()->delete();
    }

    /**
     * Handle the Guru "restored" event.
     */
    public function restored(Guru $guru): void
    {
        //
    }

    /**
     * Handle the Guru "force deleted" event.
     */
    public function forceDeleted(Guru $guru): void
    {
        //
    }
}
