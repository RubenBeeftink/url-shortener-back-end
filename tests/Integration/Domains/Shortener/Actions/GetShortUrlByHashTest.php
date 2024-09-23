<?php

declare(strict_types = 1);

namespace Tests\Integration\Domains\Shortener\Actions;

use App\Domains\Shortener\Actions\GetShortUrlByHash;
use App\Domains\Shortener\Models\ShortUrl;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

class GetShortUrlByHashTest extends TestCase
{
    public function test_it_returns_a_short_url(): void
    {
        $shortUrl = ShortUrl::factory()->create();

        $result = (new GetShortUrlByHash())->execute($shortUrl->hash);

        self::assertEquals($shortUrl->id, $result->id);
    }

    public function test_it_throws_a_not_found_exception_when_no_matching_results_are_found(): void
    {
        $this->expectException(ModelNotFoundException::class);

        (new GetShortUrlByHash())->execute('foo');
    }
}
