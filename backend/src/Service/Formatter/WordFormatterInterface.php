<?php

namespace App\Service\Formatter;

use App\Entity\Word;
use App\Enum\WordGender;

/**
 * @author Wilhelm Zwertvaegher
 */
interface WordFormatterInterface
{
    public function formatLabel(Word $word, WordGender $gender): string;
}
