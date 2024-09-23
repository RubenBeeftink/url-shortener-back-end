<?php

declare(strict_types = 1);

namespace Tests\Integration\Domains\Shortener\Actions;

use App\Domains\Shortener\Actions\CreateShortUrl;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateShortUrlTest extends TestCase
{
    public function test_it_creates_a_short_url(): void
    {
        $urlToShorten = 'https://foo-bar.com/very-long-url-that-is-too-long';
        $user = User::factory()->create();

        $shortUrl = (new CreateShortUrl())->execute($user,'test', $urlToShorten);

        self::assertDatabaseHas(
            'short_urls',
            [
                'name' => 'test',
                'url' => 'https://foo-bar.com/very-long-url-that-is-too-long',
                'hash' => $shortUrl->hash,
            ]);
    }
}
