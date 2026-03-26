<?php

namespace App\Service\Data;

interface ApiKeyGeneratorInterface
{
    public function generate(int $length = 48): string;
}
