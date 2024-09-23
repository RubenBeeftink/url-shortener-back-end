<?php

declare(strict_types = 1);

namespace Tests\Integration\Domains\Shortener\Actions;

use App\Domains\Shortener\Actions\DeleteShortUrlById;
use App\Domains\Shortener\Models\ShortUrl;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

class DeleteShortUrlByIdTest extends TestCase
{
    public function test_it_deletes_a_short_url(): void
    {
        $shorUrl = ShortUrl::factory()->create();

        (new DeleteShortUrlById())->execute($shorUrl->id);

        self::assertDatabaseEmpty('short_urls');
    }

    public function test_it_throws_a_not_found_exception_when_no_matching_results_are_found(): void
    {
        self::expectException(ModelNotFoundException::class);

        (new DeleteShortUrlById())->execute('foo');
    }
}
