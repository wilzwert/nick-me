<?php

namespace App\Service\Nick\Strategy;

use App\Dto\Result\FormattedNickWord;
use App\Entity\GrammaticalRole;
use App\Enum\Lang;
use App\Enum\WordGender;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @author Wilhelm Zwertvaegher
 */
#[AutoconfigureTag('app.word_rules')]
interface WordRules
{
    public function getLang(): Lang;

    public function resolve(GrammaticalRole $grammaticalRole, WordGender $targetGender): FormattedNickWord;
}
