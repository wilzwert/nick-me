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
    public function getLang(): Lang;

    public function format(Word $word, WordGender $targetGender): string;
}
