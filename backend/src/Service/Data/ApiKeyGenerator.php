<?php

namespace App\Service\Data;

use Random\RandomException;

class ApiKeyGenerator implements ApiKeyGeneratorInterface
{
    /**
     * @throws RandomException
     */
    public function generate(int $length = 32): string
    {
        if (0 !== $length % 2) {
            throw new \InvalidArgumentException('Length must be even');
        }

        if ($length < 16) {
            throw new \InvalidArgumentException('Length must be greater than 16');
        }

        if ($length > 64) {
            throw new \InvalidArgumentException('Length must be less than 64');
        }

        return bin2hex(random_bytes($length / 2));
    }
}
