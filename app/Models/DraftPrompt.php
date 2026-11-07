<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DraftPrompt extends Model
{
    protected $table = 'draft_prompt';

    protected $fillable = ['timestamp', 'prompt_message', 'wisata_id', 'agensi_id', 'job_id', 'job_status'];

    protected function casts(): array
    {
        return [
            'timestamp' => 'datetime',
        ];
    }

    public function wisata(): BelongsTo
    {
        return $this->belongsTo(Wisata::class, 'wisata_id', 'id');
    }

    public function agensi(): BelongsTo
    {
        return $this->belongsTo(Agensi::class, 'agensi_id', 'id');
    }
}

