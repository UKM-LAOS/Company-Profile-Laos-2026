<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'divisi_id',
        'judul_program',
        'slug',
        'location_name',
        'open_regis_panitia',
        'close_regis_panitia',
        'gform_panitia',
        'open_regis_peserta',
        'close_regis_peserta',
        'gform_peserta',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'open_regis_panitia' => 'date',
            'close_regis_panitia' => 'date',
            'open_regis_peserta' => 'date',
            'close_regis_peserta' => 'date',
        ];
    }

    /**
     * Get the division that owns this work program.
     *
     * @return BelongsTo<Divisi, $this>
     */
    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }
}
