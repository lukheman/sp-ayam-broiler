<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatDiagnosaGejala extends Model
{
    use HasFactory;

    protected $table = 'riwayat_diagnosa_gejala';

    public $timestamps = false;

    protected $fillable = [
        'id_riwayat_diagnosa',
        'id_gejala',
    ];

    /**
     * Get the riwayat_diagnosa that owns this record.
     */
    public function riwayatDiagnosa(): BelongsTo
    {
        return $this->belongsTo(RiwayatDiagnosa::class, 'id_riwayat_diagnosa');
    }

    /**
     * Get the gejala that owns this record.
     */
    public function gejala(): BelongsTo
    {
        return $this->belongsTo(Gejala::class, 'id_gejala');
    }
}
