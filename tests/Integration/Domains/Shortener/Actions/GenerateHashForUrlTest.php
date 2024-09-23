<?php

declare(strict_types = 1);

namespace Tests\Integration\Domains\Shortener\Actions;

use App\Domains\Shortener\Actions\GenerateHashForUrl;
use App\Domains\Shortener\Models\ShortUrl;
use Illuminate\Support\Str;
use Tests\TestCase;

class GenerateHashForUrlTest extends TestCase
{
    public function test_it_generates_a_hash(): void
    {
        $hash = (new GenerateHashForUrl())->execute();

        self::assertEquals(7, strlen($hash));
    }

    public function test_it_generates_a_unique_hash(): void
    {
        ShortUrl::factory()->create([
            'hash' => 'abcd123',
        ]);

        Str::createRandomStringsUsingSequence([
            'abcd123',
            'abcd123',
            'dcba321',
        ]);

        $hash = (new GenerateHashForUrl())->execute();

        self::assertEquals('dcba321', $hash);
    }
}
