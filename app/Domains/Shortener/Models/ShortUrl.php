<?php

declare(strict_types = 1);

namespace App\Domains\Shortener\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property int $user_id
 * @property string $name
 * @property string $url
 * @property string $hash
 * @property Carbon $expires_at
 *
 * @property User $user
 */
class ShortUrl extends Model
{
    use HasUuids;
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'hash',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function getShortUrl(): string
    {
        return url($this->hash);
    }

    public function isExpired(): bool
    {
        if(is_null($this->expires_at)) {
            return false;
        }

        return $this->expires_at->isBefore(Carbon::now());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
