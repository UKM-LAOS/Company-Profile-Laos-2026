<?php

namespace App\Models;

use Database\Factories\PengurusFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
            'sosmed' => 'array',
            'urutan' => 'integer',
            'aktif' => 'boolean',
        ];
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
