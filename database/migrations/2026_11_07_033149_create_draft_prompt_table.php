<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('draft_prompt', function (Blueprint $table) {
            $table->id();
            $table->timestamp('timestamp')->useCurrent();
            $table->text('prompt_message');
            $table->foreignId('wisata_id')->nullable()->unique()
                ->constrained('wisata')->cascadeOnUpdate()->cascadeOnDelete();
            $table->uuid('agensi_id');
            $table->timestamps();

            $table->foreign('agensi_id')->references('id')->on('agensi')
                ->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('draft_prompt');
    }
};
