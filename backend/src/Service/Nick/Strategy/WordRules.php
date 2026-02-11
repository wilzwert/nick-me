<?php

namespace App\Service\Nick\Strategy;

use App\Dto\Result\GeneratedNickWord;
use App\Entity\GrammaticalRole;
use App\Enum\Lang;
use App\Enum\WordGender;

/**
 * @author Wilhelm Zwertvaegher
 */
interface WordRules
{
    public function getLang(): Lang;

    public function resolve(GrammaticalRole $grammaticalRole, WordGender $targetGender): GeneratedNickWord;
}
