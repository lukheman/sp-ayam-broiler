<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penyakit extends Model
{
    use HasFactory;

    protected $table = 'penyakit';

    protected $fillable = [
        'kode_penyakit',
        'nama_penyakit',
        'solusi',
    ];

    /**
     * Get all gejala associated with this penyakit through basis_pengetahuan.
     */
    public function gejala(): BelongsToMany
    {
        return $this->belongsToMany(Gejala::class, 'basis_pengetahuan', 'id_penyakit', 'id_gejala')
            ->withPivot('bobot')
            ->withTimestamps();
    }

    /**
     * Get all basis_pengetahuan records for this penyakit.
     */
    public function basisPengetahuan(): HasMany
    {
        return $this->hasMany(BasisPengetahuan::class, 'id_penyakit');
    }

    /**
     * Get all riwayat_diagnosa for this penyakit.
     */
    public function riwayatDiagnosa(): HasMany
    {
        return $this->hasMany(RiwayatDiagnosa::class, 'id_penyakit');
    }
}
