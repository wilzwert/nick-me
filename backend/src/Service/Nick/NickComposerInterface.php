<?php

namespace App\Service\Nick;

use App\Dto\Result\ComposedNick;
use App\Entity\Qualifier;
use App\Entity\Subject;
use App\Enum\Lang;
use App\Enum\WordGender;

/**
 * @author Wilhelm Zwertvaegher
 *
 * Composes a nick based on its subject, qualifier, lang and target gender
 */
interface NickComposerInterface
{
    public function compose(Subject $subject, Qualifier $qualifier, Lang $lang, WordGender $targetGender): ComposedNick;
}
