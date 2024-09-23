<?php

declare(strict_types = 1);

namespace Tests\Integration\Domains\Shortener\Actions;

use App\Domains\Shortener\Actions\UpdateShortUrl;
use Carbon\Carbon;
use Database\Factories\Domains\Shortener\Models\ShortUrlFactory;
use Tests\TestCase;

class UpdateShortUrlTest extends TestCase
{
    public function test_it_updates_a_short_url(): void
    {
        Carbon::setTestNow(Carbon::now()->micro(0));

        $shortUrl = ShortUrlFactory::new()->create();

        $result = (new UpdateShortUrl())->execute(
            $shortUrl,
            'new-name',
            'https://new-url.com',
            Carbon::now()->addDays(5),
        );

        self::assertEquals('new-name', $result->name);
        self::assertEquals('https://new-url.com', $result->url);
        self::assertEquals(Carbon::now()->addDays(5), $result->expires_at);
    }
}
