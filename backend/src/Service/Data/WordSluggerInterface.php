<?php

namespace App\Service\Data;

/**
 * @author Wilhelm Zwertvaegher
 */
interface WordSluggerInterface
{
    public function slug(string $str): string;
}
