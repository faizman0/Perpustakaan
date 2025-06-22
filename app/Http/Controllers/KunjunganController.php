<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Anggota;
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
        $kunjungans = Kunjungan::with(['anggota.siswa', 'anggota.guru'])
            ->latest()
            ->get();
            
        return view('kunjungan.index', [
            'kunjungans' => $kunjungans,
            'key' => 'kunjungan'
        ]);
    }

    public function create()
    {
        $anggotas = Anggota::with(['siswa', 'guru'])->get();
        return view('kunjungan.create', [
            'anggotas' => $anggotas,
            'key' => 'kunjungan'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'anggota_ids' => 'required|array',
            'anggota_ids.*' => 'exists:anggotas,id',
            'tanggal_kunjungan' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->anggota_ids as $anggota_id) {
                Kunjungan::create([
                    'anggota_id' => $anggota_id,
                    'tanggal_kunjungan' => $request->tanggal_kunjungan,
                    'keterangan' => $request->keterangan,
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating kunjungan: ' . $e->getMessage());
            // Redirect based on role with error message
            $route = Auth::user()->hasRole('admin') ? 'admin.kunjungan.index' : 'petugas.kunjungan.index';
            return redirect()->route($route)
                ->with('error', 'Terjadi kesalahan saat menyimpan data kunjungan.');
        }


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

        $anggotas = Anggota::with(['siswa', 'guru'])->get();
        return view('kunjungan.edit', [
            'kunjungan' => $kunjungan,
            'anggotas' => $anggotas,
            'key' => 'kunjungan'
        ]);
    }

    public function update(Request $request, Kunjungan $kunjungan)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'tanggal_kunjungan' => 'required|date',
            'keterangan' => 'required'
        ]);

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

        $query = Kunjungan::with(['anggota.siswa', 'anggota.guru']);
        
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_kunjungan', [$startDate, $endDate]);
        }

        $kunjungans = $query->latest()->get();
            
        $pdf = PDF::loadView('kunjungan.pdf', [
            'kunjungans' => $kunjungans,
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
