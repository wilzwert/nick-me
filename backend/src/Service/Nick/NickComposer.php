<?php

namespace App\Service\Nick;

use App\Dto\Result\ComposedNick;
use App\Entity\GrammaticalRole;
use App\Entity\Qualifier;
use App\Entity\Subject;
use App\Enum\Lang;
use App\Enum\QualifierPosition;
use App\Enum\WordGender;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;

/**
 * Nick composer.
 *
 * @author Wilhelm Zwertvaegher
 */
readonly class NickComposer implements NickComposerInterface
{
    public function __construct(
        #[AutowireLocator('app.composer_rules', indexAttribute: 'index')]
        private ContainerInterface $composerRules,
        private WordFormatterInterface $wordFormatter,
    ) {
    }

    /**
     *  Build an actual Nick from a Subject, Qualifier, lang and gender
     *  In this case, put words in the right order, delegate words formatting, and applying specific rules if available.
     */
    public function compose(Subject $subject, Qualifier $qualifier, Lang $lang, WordGender $targetGender): ComposedNick
    {
        // put grammatical roles in the right order
        $grammaticalRoles = [$subject];
        if (QualifierPosition::AFTER === $qualifier->getPosition()) {
            $grammaticalRoles[] = $qualifier;
        } else {
            array_unshift($grammaticalRoles, $qualifier);
        }

        // get an array of formated GeneratedNickWord
        $formattedWords = array_map(
            fn (GrammaticalRole $grammaticalRole) => $this->wordFormatter->format($grammaticalRole, $targetGender),
            $grammaticalRoles
        );

        // assemble formated GeneratedNickWord and metadata (gender and offense level)
        $composedNick = new ComposedNick(
            $targetGender,
            $subject->getWord()->getOffenseLevel(),
            $formattedWords
        );

        // apply composing rules if available before returning
        $composer = $this->composerRules->has($lang->value) ? $this->composerRules->get($lang->value) : null;
        if ($composer) {
            return $composer->apply($composedNick, $targetGender);
        }

        return $composedNick;
    }
}
