<?php

namespace App\Translation;

/**
 * @author Wilhelm Zwertvaegher
 */
class Translator implements TranslatorInterface
{
    public function translate(string $message): string
    {
        return 'test'.$message;
    }
}
