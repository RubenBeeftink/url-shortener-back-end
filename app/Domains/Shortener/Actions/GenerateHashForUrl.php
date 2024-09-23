<?php

declare(strict_types = 1);

namespace App\Domains\Shortener\Actions;

use App\Domains\Shortener\Models\ShortUrl;
use Illuminate\Support\Str;

/**
 * Recursively create a unique random string to use as a hash for a short url.
 */
class GenerateHashForUrl
{
    public function execute(): string
    {
        $hash = Str::random(7);

        if($this->hashExists($hash)) {
            return $this->execute();
        }

        return $hash;
    }

    private function hashExists(string $hash): bool
    {
        return ShortUrl::query()
            ->where('hash', $hash)
            ->exists();
    }
}
