<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Desa extends Model
{
    protected $table = 'desa';

    protected $primaryKey = 'kode_pos';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'kode_pos',
        'nama_desa',
        'provinsi',
        'kota',
        'kecamatan',
        'nipd',
    ];

    public function kepalaDesa(): BelongsTo
    {
        return $this->belongsTo(KepalaDesa::class, 'nipd', 'nipd');
    }

    public function wisata(): HasMany
    {
        return $this->hasMany(Wisata::class, 'kode_pos', 'kode_pos');
    }
}

