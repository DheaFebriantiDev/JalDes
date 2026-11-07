<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Wisata extends Model
{
    protected $table = 'wisata';

    protected $fillable = [
        'nama_wisata',
        'jalan',
        'video_promosi',
        'gambar',
        'video',
        'deskripsi',
        'status',
        'kode_pos',
        'agensi_id',
        'nipd',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function agensi(): BelongsTo
    {
        return $this->belongsTo(Agensi::class, 'agensi_id', 'id');
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class, 'kode_pos', 'kode_pos');
    }

    public function kepalaDesa(): BelongsTo
    {
        return $this->belongsTo(KepalaDesa::class, 'nipd', 'nipd');
    }

    public function draftPrompt(): HasOne
    {
        return $this->hasOne(DraftPrompt::class, 'wisata_id', 'id');
    }
}
