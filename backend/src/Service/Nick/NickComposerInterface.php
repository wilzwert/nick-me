<?php

namespace App\Service\Nick;

use App\Dto\Result\GeneratedNickWords;
use App\Entity\Qualifier;
use App\Entity\Subject;
use App\Enum\Lang;
use App\Enum\WordGender;

/**
 * @author Wilhelm Zwertvaegher
 *
 * Formats a generated nick based on its lang and target gender
 * If a lang does not require additional rules, then there is no need to implement a formatter for this lang
 */
interface NickComposerInterface
{
    public function compose(Subject $subject, Qualifier $qualifier, Lang $lang, WordGender $targetGender): GeneratedNickWords;
}
