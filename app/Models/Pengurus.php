<?php

namespace App\Models;

use Database\Factories\PengurusFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Pengurus extends Model
{
    /** @use HasFactory<PengurusFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama',
        'jabatan',
        'periode',
        'divisi_id',
        'foto',
        'sosmed',
        'urutan',
        'aktif',
    ];

    /**
     * @var list<string>
     */
    protected $appends = ['foto_url'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'divisi_id' => 'integer',
            'sosmed' => 'array',
            'urutan' => 'integer',
            'aktif' => 'boolean',
        ];
    }

    /**
     * Division this committee member belongs to, if assigned.
     *
     * @return BelongsTo<Divisi, $this>
     */
    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }

    /**
     * Public URL of the stored photo on the "public" disk.
     *
     * @return Attribute<string|null, never>
     */
    protected function fotoUrl(): Attribute
    {
        return Attribute::get(
            fn (): ?string => $this->foto ? Storage::disk('public')->url($this->foto) : null
        );
    }
}
