<?php

namespace App\Service\Nick\Strategy;

use App\Dto\Result\ComposedNick;
use App\Enum\Lang;
use App\Enum\WordGender;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @author Wilhelm Zwertvaegher
 */
#[AutoconfigureTag('app.composer_rules')]
interface NickComposerRules
{
    public function getLang(): Lang;

    public function apply(ComposedNick $composedNick, WordGender $targetGender): ComposedNick;
}
