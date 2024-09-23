<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('short_urls', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->string('url');
            $table->string('hash');
            $table->datetime('expires_at')->nullable();

            $table->timestamps();
        });
    }
};
