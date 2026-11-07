<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kepala_desa', function (Blueprint $table) {
            $table->string('nipd')->primary();
            $table->string('nik')->unique();
            $table->string('nama');
            $table->string('nomor_telepon');
            $table->boolean('status')->default(true);
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kepala_desa');
    }
};

