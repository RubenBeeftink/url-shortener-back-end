<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Api;

use App\Domains\Shortener\Actions\CreateShortUrl;
use App\Domains\Shortener\Actions\DeleteShortUrlById;
use App\Domains\Shortener\Actions\GetShortUrlsByUser;
use App\Domains\Shortener\Actions\UpdateShortUrl;
use App\Domains\Shortener\Models\ShortUrl;
use App\Http\Requests\ShortUrl\ShortUrlStoreRequest;
use App\Http\Requests\ShortUrl\ShortUrlUpdateRequest;
use App\Http\Resources\ShortUrlResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;

class ShortUrlController
{
    public function index(): AnonymousResourceCollection
    {
        $shortUrls = app(GetShortUrlsByUser::class)->execute(auth()->user());

        return ShortUrlResource::collection($shortUrls);
    }

    public function show(ShortUrl $shortUrl): ShortUrlResource
    {
        $this->validateAccess($shortUrl);

        return ShortUrlResource::make($shortUrl);
    }

    public function store(ShortUrlStoreRequest $request): ShortUrlResource
    {
        $shortUrl = app(CreateShortUrl::class)->execute(
            auth()->user(),
            $request->getName(),
            $request->getUrl(),
            $request->getExpiresAt(),
        );

        return ShortUrlResource::make($shortUrl);
    }

    /**
     * @throws Throwable
     */
    public function update(ShortUrl $shortUrl, ShortUrlUpdateRequest $request): ShortUrlResource
    {
        $this->validateAccess($shortUrl);

        $shortUrl = app(UpdateShortUrl::class)->execute(
            $shortUrl,
            $request->getName(),
            $request->getUrl(),
            $request->getExpiresAt(),
        );

        return ShortUrlResource::make($shortUrl);
    }

    /**
     * @throws Throwable
     */
    public function destroy(ShortUrl $shortUrl): JsonResponse
    {
        $this->validateAccess($shortUrl);

        app(DeleteShortUrlById::class)->execute($shortUrl->id);

        return response()->json(null, 202);
    }

    private function validateAccess(ShortUrl $shortUrl): void
    {
        if(auth()->id() !== $shortUrl->user_id) {
            abort(403);
        }
    }
}
