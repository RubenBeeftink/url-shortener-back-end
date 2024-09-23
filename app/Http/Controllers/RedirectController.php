<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Domains\Shortener\Actions\GetShortUrlByHash;
use Illuminate\Http\RedirectResponse;

class RedirectController
{
    public function index(string $hash): RedirectResponse
    {
        $shortUrl = app(GetShortUrlByHash::class)->execute($hash);

        return redirect($shortUrl->url);
    }
}
