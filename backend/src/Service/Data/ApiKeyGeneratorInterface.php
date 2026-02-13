<?php

namespace App\Service\Data;

use App\Entity\ApiKey;

interface ApiKeyGeneratorInterface
{
    public function generate(int $length = 48): string;
}
