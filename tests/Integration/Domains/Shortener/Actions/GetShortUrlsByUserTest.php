<?php

declare(strict_types = 1);

namespace Tests\Integration\Domains\Shortener\Actions;

use App\Domains\Shortener\Actions\GetShortUrlsByUser;
use App\Domains\Shortener\Models\ShortUrl;
use App\Models\User;
use Tests\TestCase;

class GetShortUrlsByUserTest extends TestCase
{
    public function test_it_returns_all_short_urls_for_a_user(): void
    {
        $user = User::factory()->create();

        ShortUrl::factory()
            ->times(5)
            ->forUser($user)
            ->create();

        $result = (new GetShortUrlsByUser())->execute($user);

        self::assertCount(5, $result);
    }
}
