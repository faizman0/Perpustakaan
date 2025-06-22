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
            $table->string('judul','150');
            $table->string('no_inventaris','50')->unique()->nullable();
            $table->string('no_klasifikasi','50')->nullable();
            $table->string('pengarang','100');
            $table->string('penerbit','100');
            $table->string('tahun_terbit','20');
            $table->string('edisi','50')->nullable();
            $table->string('isbn','50')->unique()->nullable();
            $table->string('kolase','100')->nullable();
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
