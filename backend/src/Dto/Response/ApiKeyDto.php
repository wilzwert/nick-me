<?php

namespace App\Dto\Response;

readonly class ApiKeyDto
{
    public function __construct(public string $key)
    {
    }
}
