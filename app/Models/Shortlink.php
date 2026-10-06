<?php

namespace App\Models;

use Database\Factories\ShortlinkFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shortlink extends Model
{
    /** @use HasFactory<ShortlinkFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'destination_url',
        'short_code',
        'click_count',
        'is_active',
        'expires_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'click_count' => 'integer',
            'is_active' => 'boolean',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Get the user who created this shortlink.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
