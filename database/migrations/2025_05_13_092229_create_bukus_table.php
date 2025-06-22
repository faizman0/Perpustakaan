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
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('no_inventaris')->unique()->nullable();
            $table->string('no_klasifikasi')->unique()->nullable();
            $table->string('pengarang');
            $table->string('penerbit');
            $table->year('tahun_terbit');
            $table->year('edisi')->nullable();
            $table->string('isbn')->unique();
            $table->string('kolase')->nullable();
            $table->integer('jumlah');
            $table->text('keterangan')->nullable();
            $table->foreignId('kategori_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
