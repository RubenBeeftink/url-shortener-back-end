<?php

declare(strict_types = 1);

namespace App\Domains\Shortener\Actions;

use App\Domains\Shortener\Models\ShortUrl;
use App\Models\User;
use Illuminate\Support\Collection;

class GetShortUrlsByUser
{
    /**
     * @return Collection<ShortUrl>
     */
    public function execute(User $user): Collection
    {
        return $user->shortUrls()
            ->get();
    }
}
