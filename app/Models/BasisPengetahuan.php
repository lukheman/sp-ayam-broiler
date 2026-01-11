<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BasisPengetahuan extends Model
{
    use HasFactory;

    protected $table = 'basis_pengetahuan';

    protected $fillable = [
        'id_penyakit',
        'id_gejala',
    ];

    /**
     * Get the penyakit that owns this basis_pengetahuan.
     */
    public function penyakit(): BelongsTo
    {
        return $this->belongsTo(Penyakit::class, 'id_penyakit');
    }

    /**
     * Get the gejala that owns this basis_pengetahuan.
     */
    public function gejala(): BelongsTo
    {
        return $this->belongsTo(Gejala::class, 'id_gejala');
    }
}
