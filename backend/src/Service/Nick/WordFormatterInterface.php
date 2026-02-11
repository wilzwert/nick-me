<?php

namespace App\Service\Nick;

use App\Dto\Result\GeneratedNickWord;
use App\Entity\GrammaticalRole;
use App\Enum\WordGender;

/**
 * @author Wilhelm Zwertvaegher
 */
interface WordFormatterInterface
{
    public function format(GrammaticalRole $grammaticalRole, WordGender $gender): GeneratedNickWord;
}
