<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('draft_prompt', function (Blueprint $table) {
            $table->string('job_id')->nullable()->after('agensi_id');
            $table->string('job_status')->default('pending')->after('job_id');
        });
    }

    public function down(): void
    {
        Schema::table('draft_prompt', function (Blueprint $table) {
            $table->dropColumn(['job_id', 'job_status']);
        });
    }
};
