<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gejala extends Model
{
    use HasFactory;

    protected $table = 'gejala';

    protected $fillable = [
        'kode_gejala',
        'nama_gejala',
    ];

    /**
     * Get all penyakit associated with this gejala through basis_pengetahuan.
     */
    public function penyakit(): BelongsToMany
    {
        return $this->belongsToMany(Penyakit::class, 'basis_pengetahuan', 'id_gejala', 'id_penyakit')
            ->withPivot('bobot')
            ->withTimestamps();
    }

    /**
     * Get all basis_pengetahuan records for this gejala.
     */
    public function basisPengetahuan(): HasMany
    {
        return $this->hasMany(BasisPengetahuan::class, 'id_gejala');
    }

    /**
     * Get all riwayat_diagnosa_gejala records for this gejala.
     */
    public function riwayatDiagnosaGejala(): HasMany
    {
        return $this->hasMany(RiwayatDiagnosaGejala::class, 'id_gejala');
    }
}
