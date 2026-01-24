<?php

namespace App\Translation;

/**
 * @author Wilhelm Zwertvaegher
 */
interface TranslatorInterface
{
    public function translate(string $message): string;
}
