<?php

namespace App\Service\Nick\Strategy;

use App\Dto\Result\GeneratedNickWords;
use App\Enum\Lang;
use App\Enum\WordGender;

/**
 * @author Wilhelm Zwertvaegher
 */
interface NickComposerRules
{
    public function getLang(): Lang;

    public function apply(GeneratedNickWords $generatedNickWords, WordGender $targetGender): GeneratedNickWords;
}
