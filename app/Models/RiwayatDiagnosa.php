<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RiwayatDiagnosa extends Model
{
    use HasFactory;

    protected $table = 'riwayat_diagnosa';

    protected $fillable = [
        'tanggal',
        'nama',
        'alamat',
        'telepon',
        'id_penyakit',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Get the penyakit that owns this riwayat_diagnosa.
     */
    public function penyakit(): BelongsTo
    {
        return $this->belongsTo(Penyakit::class, 'id_penyakit');
    }

    /**
     * Get all gejala associated with this riwayat_diagnosa.
     */
    public function gejala(): BelongsToMany
    {
        return $this->belongsToMany(Gejala::class, 'riwayat_diagnosa_gejala', 'id_riwayat_diagnosa', 'id_gejala');
    }

    /**
     * Get all riwayat_diagnosa_gejala records for this riwayat_diagnosa.
     */
    public function riwayatDiagnosaGejala(): HasMany
    {
        return $this->hasMany(RiwayatDiagnosaGejala::class, 'id_riwayat_diagnosa');
    }
}
