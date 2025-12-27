<?php

namespace App\Service\Formatter\Strategy;

use App\Entity\Word;
use App\Enum\Lang;
use App\Enum\WordGender;

/**
 * @author Wilhelm Zwertvaegher
 */
interface FormatterStrategyInterface
{
    public function supports(Word $word): bool;

    public function format(Word $word, WordGender $gender): string;
}
