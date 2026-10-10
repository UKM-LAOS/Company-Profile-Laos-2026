<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Program extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'divisi_id',
        'pengurus_id',
        'judul_program',
        'slug',
        'location_name',
        'deskripsi',
        'foto',
        'open_regis_panitia',
        'close_regis_panitia',
        'gform_panitia',
        'open_regis_peserta',
        'close_regis_peserta',
        'gform_peserta',
    ];

    /**
     * @var list<string>
     */
    protected $appends = ['foto_url', 'status_panitia', 'status_peserta'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'open_regis_panitia' => 'date:Y-m-d',
            'close_regis_panitia' => 'date:Y-m-d',
            'open_regis_peserta' => 'date:Y-m-d',
            'close_regis_peserta' => 'date:Y-m-d',
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

    /**
     * Status of panitia registration: 'none', 'upcoming', 'open', 'closed'.
     *
     * @return Attribute<string, never>
     */
    protected function statusPanitia(): Attribute
    {
        return Attribute::get(function (): string {
            if (! $this->open_regis_panitia || ! $this->close_regis_panitia) {
                return 'none';
            }

            $today = now()->startOfDay();
            $open = $this->open_regis_panitia->copy()->startOfDay();
            $close = $this->close_regis_panitia->copy()->endOfDay();

            if ($today->lt($open)) {
                return 'upcoming';
            }

            if ($today->lte($close)) {
                return 'open';
            }

            return 'closed';
        });
    }

    /**
     * Status of peserta registration: 'upcoming', 'open', 'closed'.
     *
     * @return Attribute<string, never>
     */
    protected function statusPeserta(): Attribute
    {
        return Attribute::get(function (): string {
            if (! $this->open_regis_peserta || ! $this->close_regis_peserta) {
                return 'none';
            }

            $today = now()->startOfDay();
            $open = $this->open_regis_peserta->copy()->startOfDay();
            $close = $this->close_regis_peserta->copy()->endOfDay();

            if ($today->lt($open)) {
                return 'upcoming';
            }

            if ($today->lte($close)) {
                return 'open';
            }

            return 'closed';
        });
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

    /**
     * Get the pengurus (PIC) responsible for this work program.
     *
     * @return BelongsTo<Pengurus, $this>
     */
    public function pengurus(): BelongsTo
    {
        return $this->belongsTo(Pengurus::class, 'pengurus_id');
    }
}
