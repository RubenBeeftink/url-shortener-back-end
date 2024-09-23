<?php

declare(strict_types = 1);

namespace App\Domains\Shortener\Actions;

use App\Domains\Shortener\Models\ShortUrl;
use Throwable;

class DeleteShortUrlById
{
    /**
     * @throws Throwable
     */
    public function execute(string $uuid): void
    {
        ShortUrl::query()
            ->findOrFail($uuid)
            ->deleteOrFail();
    }
}
