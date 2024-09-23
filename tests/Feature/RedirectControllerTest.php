<?php

declare(strict_types = 1);

namespace Tests\Feature;

use App\Domains\Shortener\Actions\GetShortUrlByHash;
use App\Domains\Shortener\Models\ShortUrl;
use Carbon\Carbon;
use Tests\TestCase;

class RedirectControllerTest extends TestCase
{
    public function test_it_redirects_based_on_a_url(): void
    {
        /**
         * @var ShortUrl $shortUrl
         */
        $shortUrl = ShortUrl::factory()->create();

        $this->mock(GetShortUrlByHash::class, function ($mock) use ($shortUrl) {
            $mock->shouldReceive('execute')
                ->once()
                ->withArgs(function(string $hash) use ($shortUrl) {
                    return $hash === $shortUrl->hash;
                })
                ->andReturn($shortUrl);
        });

        $this->get(route('index', $shortUrl->hash))
            ->assertRedirect($shortUrl->url);
    }

    public function test_it_returns_404_when_no_matching_short_url_is_found(): void
    {
        $this->get(route('index', 'foo'))
            ->assertNotFound();
    }

    public function test_it_returns_404_when_url_is_expired(): void
    {
        /**
         * @var ShortUrl $shortUrl
         */
        $shortUrl = ShortUrl::factory([
            'expires_at' => Carbon::now()->subWeeks(5),
        ])->create();

        $this->get(route('index', $shortUrl->hash))
            ->assertNotFound();
    }
}
