<?php

declare(strict_types = 1);

namespace App\Domains\Shortener\Actions;

use App\Domains\Shortener\Models\ShortUrl;
use App\Models\User;
use Carbon\Carbon;

class CreateShortUrl
{
    public function execute(User $user, string $name, string $url, ?Carbon $expiresAt = null): ShortUrl
    {
        return $user->shortUrls()
            ->create([
                'name' => $name,
                'url' => $url,
                'hash' => app(GenerateHashForUrl::class)->execute(),
                'expires_at' => $expiresAt,
            ]);
    }
}
