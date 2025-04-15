<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Exports\MembersExport;
use App\Imports\MembersImport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class MemberController extends Controller
{
    /**
     * Menampilkan daftar member.
     */
    public function index()
    {
        $members = Member::all();
        return view('members.index', [
            'members' => $members,
            'key' => 'member',
        ]);
    }

    /**
     * Menampilkan form untuk menambahkan member baru.
     */
    public function create()
    {
        return view('members.create', [
            'key' => 'member',
        ]);
    }

    /**
     * Menyimpan member baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|unique:members,nis',
            'class' => 'required|integer',
        ]);

        Member::create($request->all());

        return redirect()->route('members.index')->with('success', 'Member created successfully.');
    }

    /**
     * Menampilkan detail member.
     */
    public function show(Member $member)
    {
        return view('members.show', compact('member'));
    }

    /**
     * Menampilkan form untuk mengedit member.
     */
    public function edit(Member $member)
    {
        return view('members.edit', [
            'member' => $member,
            'key' => 'member',
        ]); 
    }

    /**
     * Mengupdate data member di database.
     */
    public function update(Request $request, Member $member)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|unique:members,nis,' . $member->id,
            'class' => 'required|integer',
        ]);

        $member->update($request->all());

        return redirect()->route('members.index')->with('success', 'Member updated successfully.');
    }

    /**
     * Menghapus member dari database.
     */
    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->route('members.index')->with('success', 'Member deleted successfully.');
    }

    /**
     * Export data member ke Excel
     */
    public function exportExcel()
    {
        return Excel::download(new MembersExport, 'members.xlsx');
    }

    /**
     * Export data member ke PDF
     */
    public function exportPDF(): mixed
    {
        $members = Member::all();
        $pdf = PDF::loadView('members.pdf', compact('members'));
        return $pdf->download('members.pdf');
    }

    /**
     * Import data member dari Excel
     */
    public function importExcel(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls|max:2048' // Max 2MB
    ]);

    try {
        Excel::import(new MembersImport, $request->file('file'));
        return back()->with('success', 'Data berhasil diimport!');
    } catch (\Exception $e) {
        return back()->with('error', 'Error: '.$e->getMessage());
    }
}
}