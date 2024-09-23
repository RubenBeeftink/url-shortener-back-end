<?php

declare(strict_types = 1);

namespace App\Http\Requests\ShortUrl;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ShortUrlUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'string',
                'max:32'
            ],
            'url' => [
                'sometimes',
                'url',
            ],
            'expires_at' => [
                'sometimes',
                'date'
            ],
        ];
    }

    public function getName(): ?string
    {
        return $this->input('name');
    }

    public function getUrl(): ?string
    {
        return $this->input('url');
    }

    public function getExpiresAt(): ?Carbon
    {
        return Carbon::parse($this->input('expires_at'));
    }
}
