<?php

// File untuk testing PDF export pengembalian
// Jalankan dengan: php test_pdf.php

require_once 'vendor/autoload.php';

use App\Models\Pengembalian;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;

try {
    echo "Testing PDF export...\n";
    
    // Test query data
    $pengembalians = Pengembalian::with(['peminjaman.anggota.siswa', 'peminjaman.anggota.guru', 'peminjaman.buku.kategori'])->get();
    echo "Pengembalian count: " . $pengembalians->count() . "\n";
    
    $peminjamanBelumKembali = Peminjaman::whereDoesntHave('pengembalian')
        ->with(['buku', 'anggota.siswa', 'anggota.guru'])
        ->get();
    echo "Peminjaman aktif count: " . $peminjamanBelumKembali->count() . "\n";
    
    // Test PDF generation
    $pdf = PDF::loadView('pengembalian.pdf', [
        'pengembalians' => $pengembalians,
        'peminjamanBelumKembali' => $peminjamanBelumKembali,
        'startDate' => null,
        'endDate' => null
    ]);
    
    echo "PDF generated successfully!\n";
    
    // Save to file for testing
    $pdf->save('test_pengembalian.pdf');
    echo "PDF saved as test_pengembalian.pdf\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
} 