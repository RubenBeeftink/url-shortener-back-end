<?php

declare(strict_types = 1);

namespace Tests\Feature\Api;

use App\Domains\Shortener\Actions\CreateShortUrl;
use App\Domains\Shortener\Actions\DeleteShortUrlById;
use App\Domains\Shortener\Actions\GetShortUrlsByUser;
use App\Domains\Shortener\Actions\UpdateShortUrl;
use App\Domains\Shortener\Models\ShortUrl;
use App\Models\User;
use Carbon\Carbon;
use Mockery\MockInterface;
use Tests\TestCase;

class ShortUrlControllerTest extends TestCase
{
    public function test_index_returns_a_list_of_short_urls_for_a_user(): void
    {
        $user = User::factory()->create();

        $this->mock(GetShortUrlsByUser::class, function(MockInterface $mock) use ($user) {
            $shortUrls = ShortUrl::factory()
                ->times(5)
                ->forUser($user)
                ->create();

            $mock->shouldReceive('execute')
                ->once()
                ->andReturn($shortUrls);
        });

        $this->actingAs($user)->get(route('short-url.index'))
            ->assertOk()
            ->assertJsonCount(5, 'data');
    }

    public function test_show_returns_a_short_url(): void
    {
        $user = User::factory()->create();

        $shortUrl = ShortUrl::factory()
            ->forUser($user)
            ->create();

        $this->actingAs($user)->get(route('short-url.show', $shortUrl->id))
            ->assertOk()
            ->assertJsonFragment($shortUrl->toArray());
    }

    public function test_show_returns_403_when_attempting_to_access_another_users_short_url(): void
    {
        $user = User::factory()->create();
        $unauthorizedUser = User::factory()->create();

        $shortUrl = ShortUrl::factory()
            ->forUser($user)
            ->create();

        $this->actingAs($unauthorizedUser)->get(route('short-url.show', $shortUrl->id))
            ->assertForbidden();
    }

    public function test_store_creates_a_new_short_url(): void
    {
        Carbon::setTestNow(Carbon::now()->micro(0));

        /**
         * @var User $authenticatedUser
         */
        $authenticatedUser = User::factory()->create();

        $this->mock(CreateShortUrl::class, function(MockInterface $mock) use ($authenticatedUser) {
            $mock->shouldReceive('execute')
                ->once()
                ->withArgs(function(User $user, string $name, string $url, Carbon $expiresAt) use ($authenticatedUser) {
                    return
                        $user->id === $authenticatedUser->id &&
                        $name === 'test' &&
                        $url === 'https://verylongurl.com' &&
                        $expiresAt->toDateString() === Carbon::now()->addDays(7)->toDateString();
                })
                ->andReturn(ShortUrl::factory()->create([
                    'user_id' => $authenticatedUser->id,
                    'name' => 'test',
                    'url' => 'https://verylongurl.com',
                    'expires_at' => Carbon::now()->addDays(7),
                ]));
        });

        $this->actingAs($authenticatedUser)
            ->post(route('short-url.store'), [
                'name' => 'test',
                'url' => 'https://verylongurl.com',
                'expires_at' => Carbon::now()->addDays(7),
            ])
            ->assertCreated()
            ->assertJsonFragment([
                'name' => 'test',
                'url' => 'https://verylongurl.com',
                'expires_at' => Carbon::now()->addDays(7),
            ]);
    }

    public function test_update_updates_an_existing_short_url(): void
    {
        Carbon::setTestNow(Carbon::now()->micro(0));

        $user = User::factory()->create();

        $shortUrl = ShortUrl::factory()
            ->forUser($user)
            ->create();

        $this->mock(UpdateShortUrl::class, function(MockInterface $mock) use ($shortUrl) {
            $mock->shouldReceive('execute')
                ->once()
                ->withArgs(function(ShortUrl $shortUrlArgument, string $name, string $url, Carbon $expiresAt) use ($shortUrl) {
                    return
                        $shortUrl->id === $shortUrlArgument->id &&
                        $name === 'new-name' &&
                        $url === 'https://very-long-new-url.com' &&
                        $expiresAt->toDateString() === Carbon::now()->addDays(8)->toDateString();
                })
                ->andReturn($shortUrl->fill([
                    'name' => 'new-name',
                    'url' => 'https://very-long-new-url.com',
                    'expires_at' => Carbon::now()->addDays(8),
                ]));
        });

        $this->actingAs($user)->patch(route('short-url.update', $shortUrl->id), [
            'name' => 'new-name',
            'url' => 'https://very-long-new-url.com',
            'expires_at' => Carbon::now()->addDays(8),
        ])
            ->assertCreated()
            ->assertJsonFragment([
                'name' => 'new-name',
                'url' => 'https://very-long-new-url.com',
                'expires_at' => Carbon::now()->addDays(8),
            ]);
    }

    public function test_update_returns_403_when_unauthorized_user_attempts_to_update_an_existing_short_url(): void
    {
        $user = User::factory()->create();
        $unauthorizedUser = User::factory()->create();

        $shortUrl = ShortUrl::factory()
            ->forUser($user)
            ->create();

        $this->mock(UpdateShortUrl::class, function(MockInterface $mock) use ($shortUrl) {
            $mock->shouldReceive('execute')
                ->never();
        });

        $this->actingAs($unauthorizedUser)
            ->patch(route('short-url.update', $shortUrl->id), [
                'name' => 'new-name',
            ])
            ->assertForbidden();
    }

    public function test_destroy_deletes_an_existing_short_url(): void
    {
        $user = User::factory()->create();

        $shortUrl = ShortUrl::factory()
            ->forUser($user)
            ->create();

        $this->mock(DeleteShortUrlById::class, function(MockInterface $mock) use ($shortUrl) {
            $mock->shouldReceive('execute')
                ->once()
                ->withArgs(function(string $shortUrlId) use ($shortUrl) {
                    return $shortUrl->id === $shortUrlId;
                });
        });

        $this->actingAs($user)
            ->delete(route('short-url.destroy', $shortUrl->id))
            ->assertAccepted();
    }

    public function test_destroy_returns_403_when_unauthorized_user_attempts_to_delete_an_existing_short_url(): void
    {
        $user = User::factory()->create();
        $unauthorizedUser = User::factory()->create();

        $shortUrl = ShortUrl::factory()
            ->forUser($user)
            ->create();

        $this->mock(DeleteShortUrlById::class, function(MockInterface $mock) use ($shortUrl) {
            $mock->shouldReceive('execute')
                ->never();
        });

        $this->actingAs($unauthorizedUser)
            ->delete(route('short-url.destroy', $shortUrl->id))
            ->assertForbidden();
    }
}
