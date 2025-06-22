<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\KunjunganExports;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class KunjunganController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $kunjunganSiswa = Kunjungan::with(['siswa.kelas'])
            ->whereNotNull('siswa_id')
            ->latest()
            ->get();
            
        $kunjunganGuru = Kunjungan::with(['guru'])
            ->whereNotNull('guru_id')
            ->latest()
            ->get();
            
        return view('kunjungan.index', [
            'kunjunganSiswa' => $kunjunganSiswa,
            'kunjunganGuru' => $kunjunganGuru,
            'key' => 'kunjungan'
        ]);
    }

    public function create()
    {
        $siswas = Siswa::all();
        $gurus = Guru::all();
        return view('kunjungan.create', [
            'siswas' => $siswas,
            'gurus' => $gurus,
            'key' => 'kunjungan'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'nullable|exists:siswas,id',
            'guru_id' => 'nullable|exists:gurus,id',
            'tanggal_kunjungan' => 'required|date',
            'keterangan' => 'required'
        ]);

        // Validasi bahwa salah satu dari siswa_id atau guru_id harus diisi
        if (!$request->siswa_id && !$request->guru_id) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['siswa_id' => 'Pilih salah satu siswa atau guru']);
        }

        // Validasi bahwa tidak boleh memilih keduanya
        if ($request->siswa_id && $request->guru_id) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['siswa_id' => 'Hanya bisa memilih salah satu siswa atau guru']);
        }

        $kunjungan = Kunjungan::create($request->all());

        // Redirect berdasarkan role
        if (Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.kunjungan.index')
                ->with('success', 'Data kunjungan berhasil ditambahkan');
        } else {
            return redirect()->route('petugas.kunjungan.index')
                ->with('success', 'Data kunjungan berhasil ditambahkan');
        }
    }

    public function edit(Kunjungan $kunjungan)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $siswas = Siswa::all();
        $gurus = Guru::all();
        return view('kunjungan.edit', [
            'kunjungan' => $kunjungan,
            'siswas' => $siswas,
            'gurus' => $gurus,
            'key' => 'kunjungan'
        ]);
    }

    public function update(Request $request, Kunjungan $kunjungan)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $request->validate([
            'siswa_id' => 'nullable|exists:siswas,id',
            'guru_id' => 'nullable|exists:gurus,id',
            'tanggal_kunjungan' => 'required|date',
            'keterangan' => 'required'
        ]);

        // Validasi bahwa salah satu dari siswa_id atau guru_id harus diisi
        if (!$request->siswa_id && !$request->guru_id) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['siswa_id' => 'Pilih salah satu siswa atau guru']);
        }

        // Validasi bahwa tidak boleh memilih keduanya
        if ($request->siswa_id && $request->guru_id) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['siswa_id' => 'Hanya bisa memilih salah satu siswa atau guru']);
        }

        $kunjungan->update($request->all());

        return redirect()->route('admin.kunjungan.index')
            ->with('success', 'Data kunjungan berhasil diperbarui');
    }

    public function destroy(Kunjungan $kunjungan)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $kunjungan->delete();

        return redirect()->back()
            ->with('success', 'Data kunjungan berhasil dihapus');
    }

    public function exportPDF(Request $request)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Kunjungan::with(['siswa.kelas', 'guru']);
        
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_kunjungan', [$startDate, $endDate]);
        }

        $kunjunganSiswa = (clone $query)
            ->whereNotNull('siswa_id')
            ->latest()
            ->get();
            
        $kunjunganGuru = (clone $query)
            ->whereNotNull('guru_id')
            ->latest()
            ->get();
            
        $pdf = PDF::loadView('kunjungan.pdf', [
            'kunjunganSiswa' => $kunjunganSiswa,
            'kunjunganGuru' => $kunjunganGuru,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
        
        return $pdf->download('kunjungan.pdf');
    }

    public function exportExcel(Request $request)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        return Excel::download(
            new KunjunganExports($startDate, $endDate), 
            'kunjungan.xlsx'
        );
    }
}
