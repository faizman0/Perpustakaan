<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $anggotas = Anggota::with(['siswa', 'guru'])->get();
        return view('anggota.index', [
            'anggotas'=>$anggotas,
            'key'=>'anggota'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Anggota $anggota)
    {
        $anggota->load(['siswa', 'guru', 'kunjungans', 'peminjamen.buku']);
        return view('anggota.show', [
            'anggota' => $anggota,
            'key' => 'anggota'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Anggota $anggota)
    {
        try {
            // Hapus semua kunjungan terkait
            $anggota->kunjungans()->delete();

            // Hapus semua peminjaman terkait
            $anggota->peminjamen()->delete();

            $anggota->delete();

            return redirect()->route('admin.anggota.index')
                ->with('success', 'Anggota berhasil dihapus');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $siswas = Siswa::whereDoesntHave('anggota')->get();
        $gurus = Guru::whereDoesntHave('anggota')->get();
        return view('anggota.create', [
            'siswas' => $siswas,
            'gurus' => $gurus,
            'key'=>'anggota'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:Siswa,Guru',
            'id' => 'required|integer',
        ]);

        $isSiswa = $request->tipe == 'Siswa';

        Anggota::create([
            'kode_anggota' => Anggota::generateKodeAnggota(),
            'siswa_id' => $isSiswa ? $request->id : null,
            'guru_id' => !$isSiswa ? $request->id : null,
            'status' => 'aktif'
        ]);

        $successMessage = 'Anggota berhasil ditambahkan';

        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.anggota.index')->with('success', $successMessage);
        } else {
            return redirect()->route('petugas.anggota.index')->with('success', $successMessage);
        }
    }
} 