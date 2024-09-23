<?php

declare(strict_types = 1);

namespace App\Http\Requests\ShortUrl;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ShortUrlStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:32'
            ],
            'url' => [
                'required',
                'url',
            ],
            'expires_at' => [
                'sometimes',
                'nullable',
                'date'
            ],
        ];
    }

    public function getName(): string
    {
        return $this->input('name');
    }

    public function getUrl(): string
    {
        return $this->input('url');
    }

    public function getExpiresAt(): ?Carbon
    {
        if(is_null($this->input('expires_at'))) {
            return null;
        }

        return Carbon::parse($this->input('expires_at'));
    }
}
