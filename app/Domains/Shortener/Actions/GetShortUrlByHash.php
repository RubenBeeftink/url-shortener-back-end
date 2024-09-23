<?php

declare(strict_types = 1);

namespace App\Domains\Shortener\Actions;

use App\Domains\Shortener\Models\ShortUrl;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetShortUrlByHash
{
    /**
     * @throws ModelNotFoundException
     */
    public function execute(string $hash): ShortUrl
    {
        return ShortUrl::query()
            ->where('hash', $hash)
            ->firstOrFail();
    }
}
