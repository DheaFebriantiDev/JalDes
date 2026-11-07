<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class KepalaDesa extends Model
{
    protected $table = 'kepala_desa';

    protected $primaryKey = 'nipd';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['nipd', 'nik', 'nama', 'nomor_telepon', 'status', 'user_id'];

    protected function casts(): array
    {
        return ['status' => 'boolean'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function desa(): HasOne
    {
        return $this->hasOne(Desa::class, 'nipd', 'nipd');
    }

    public function wisata(): HasMany
    {
        return $this->hasMany(Wisata::class, 'nipd', 'nipd');
    }
}

