<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('desa', function (Blueprint $table) {
            $table->unsignedInteger('kode_pos')->primary();
            $table->string('nama_desa');
            $table->string('provinsi');
            $table->string('kota');
            $table->string('kecamatan');
            $table->string('nipd')->nullable()->unique();
            $table->timestamps();

            $table->foreign('nipd')->references('nipd')->on('kepala_desa')
                ->cascadeOnUpdate()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('desa');
    }
};
