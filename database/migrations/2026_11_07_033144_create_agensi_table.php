<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agensi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nomor_telepon');
            $table->string('nama_agensi');
            $table->text('deskripsi')->nullable();
            $table->string('npwp')->nullable()->unique();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agensi');
    }
};
