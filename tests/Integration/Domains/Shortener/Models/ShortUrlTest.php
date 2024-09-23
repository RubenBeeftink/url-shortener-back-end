<?php

declare(strict_types = 1);

namespace Tests\Integration\Domains\Shortener\Models;

use App\Domains\Shortener\Models\ShortUrl;
use Tests\TestCase;

class ShortUrlTest extends TestCase
{
    public function test_it_returns_a_short_url(): void
    {
        /**
         * @var ShortUrl $shortUrl
         */
        $shortUrl = ShortUrl::factory()->create();

        self::assertEquals(
            sprintf('https://short-url.test/%s', $shortUrl->hash),
            $shortUrl->getShortUrl(),
        );
    }

    public function test_it_has_a_user_relation(): void
    {
        /**
         * @var ShortUrl $shortUrl
         */
        $shortUrl = ShortUrl::factory()->create();

        self::assertEquals($shortUrl->user_id, $shortUrl->user()->first()->id);
    }
}
