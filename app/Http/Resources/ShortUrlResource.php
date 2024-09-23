<?php

declare(strict_types = 1);

namespace App\Http\Resources;

use App\Domains\Shortener\Models\ShortUrl;
use Illuminate\Http\Resources\Json\JsonResource;

class ShortUrlResource extends JsonResource
{
    /** @var ShortUrl */
    public $resource;

    public function toArray($request): array
    {
        return array_merge(
            $this->resource->toArray(),
            ['short_url' => $this->resource->getShortUrl()],
        );
    }
}
