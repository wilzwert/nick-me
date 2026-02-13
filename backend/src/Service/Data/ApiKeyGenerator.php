<?php

namespace App\Service\Data;

use Random\RandomException;

class ApiKeyGenerator implements ApiKeyGeneratorInterface
{
    /**
     * @throws RandomException
     */
    public function generate(int $length = 48): string
    {
        return bin2hex(random_bytes($length));
    }
}
