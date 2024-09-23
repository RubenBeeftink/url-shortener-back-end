<?php

declare(strict_types = 1);

namespace App\Domains\Shortener\Actions;

use App\Domains\Shortener\Models\ShortUrl;
use Carbon\Carbon;
use Throwable;

class UpdateShortUrl
{
    /**
     * @throws Throwable
     */
    public function execute(ShortUrl $shortUrl, string $name, string $newUrl, ?Carbon $expiresAt = null): ShortUrl
    {
        $shortUrl->fill([
            'name' => $name,
            'url' => $newUrl,
            'expires_at' => $expiresAt,
        ])->saveOrFail();

        return $shortUrl->refresh();
    }
}
