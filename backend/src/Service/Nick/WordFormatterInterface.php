<?php

namespace App\Service\Nick;

use App\Dto\Result\FormattedNickWord;
use App\Entity\GrammaticalRole;
use App\Enum\WordGender;

/**
 *  Formats a GrammaticalRole based on its target gender
 *  If a lang does not require additional rules, then there is no need to implement a formatter for this lang.
 *
 * @author Wilhelm Zwertvaegher
 */
interface WordFormatterInterface
{
    public function format(GrammaticalRole $grammaticalRole, WordGender $gender): FormattedNickWord;
}
