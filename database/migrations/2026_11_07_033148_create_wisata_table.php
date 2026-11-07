<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wisata', function (Blueprint $table) {
            $table->id();
            $table->string('nama_wisata');
            $table->string('jalan');
            $table->string('video_promosi');
            $table->string('gambar');
            $table->string('video');
            $table->text('deskripsi')->nullable();
            $table->boolean('status')->nullable();

            $table->unsignedInteger('kode_pos')->nullable();
            $table->uuid('agensi_id');
            $table->string('nipd');
            $table->timestamps();

            $table->foreign('kode_pos')->references('kode_pos')->on('desa')
                ->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('agensi_id')->references('id')->on('agensi')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('nipd')->references('nipd')->on('kepala_desa')
                ->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wisata');
    }
};
