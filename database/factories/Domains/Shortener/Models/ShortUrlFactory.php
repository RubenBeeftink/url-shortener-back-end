<?php

declare(strict_types = 1);

namespace Database\Factories\Domains\Shortener\Models;

use App\Domains\Shortener\Models\ShortUrl;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ShortUrlFactory extends Factory
{
    protected $model = ShortUrl::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word,
            'url' => $this->faker->url,
            'hash' => str::random(7),
        ];
    }

    public function forUser(?User $user): self
    {
        return $this->state([
           'user_id' => $user?->id ?? User::factory(),
        ]);
    }
}
