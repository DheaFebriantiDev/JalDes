<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agensi extends Model
{
    use HasUuids;

    protected $table = 'agensi';

    protected $fillable = ['id', 'nomor_telepon', 'nama_agensi', 'deskripsi', 'npwp', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wisata(): HasMany
    {
        return $this->hasMany(Wisata::class, 'agensi_id');
    }

    public function draftPrompts(): HasMany
    {
        return $this->hasMany(DraftPrompt::class, 'agensi_id');
    }
}

