<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengurus extends Model
{
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
}
