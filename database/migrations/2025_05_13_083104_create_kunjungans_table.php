<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kunjungans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->nullable()->constrained('siswas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('guru_id')->nullable()->constrained('gurus')->onDelete('cascade')->onUpdate('cascade');
            $table->date('tanggal_kunjungan');
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungans');
    }
};
