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
        Schema::table('draft_prompt', function (Blueprint $table) {
            if (!Schema::hasColumn('draft_prompt', 'job_id')) {
                $table->string('job_id')->nullable()->after('agensi_id');
            }
            if (!Schema::hasColumn('draft_prompt', 'job_status')) {
                $table->string('job_status')->default('pending')->after('job_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('draft_prompt', function (Blueprint $table) {
            if (Schema::hasColumn('draft_prompt', 'job_status')) {
                $table->dropColumn('job_status');
            }
            if (Schema::hasColumn('draft_prompt', 'job_id')) {
                $table->dropColumn('job_id');
            }
        });
    }
};
