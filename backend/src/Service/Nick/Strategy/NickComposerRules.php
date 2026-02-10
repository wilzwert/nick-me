<?php

namespace App\Service\Nick\Strategy;

use App\Dto\Result\GeneratedNickWord;
use App\Dto\Result\GeneratedNickWords;
use App\Enum\Lang;
use App\Enum\WordGender;

/**
 * @author Wilhelm Zwertvaegher
 */
interface NickComposerRules
{
    public function getLang(): Lang;

    /**
     * @param GeneratedNickWords $generatedNickWords
     * @param WordGender $targetGender
     * @return GeneratedNickWords
     */
    public function apply(GeneratedNickWords $generatedNickWords, WordGender $targetGender): GeneratedNickWords;
}
