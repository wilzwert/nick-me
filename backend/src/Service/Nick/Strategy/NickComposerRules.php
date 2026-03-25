<?php

namespace App\Service\Nick\Strategy;

use App\Dto\Result\ComposedNick;
use App\Enum\Lang;
use App\Enum\WordGender;

/**
 * @author Wilhelm Zwertvaegher
 */
interface NickComposerRules
{
    public function getLang(): Lang;

    public function apply(ComposedNick $composedNick, WordGender $targetGender): ComposedNick;
}
