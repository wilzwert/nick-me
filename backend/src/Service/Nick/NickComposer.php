<?php

namespace App\Service\Nick;

use App\Dto\Result\ComposedNick;
use App\Entity\GrammaticalRole;
use App\Entity\Qualifier;
use App\Entity\Subject;
use App\Enum\Lang;
use App\Enum\QualifierPosition;
use App\Enum\WordGender;
use App\Service\Nick\Strategy\NickComposerRules;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

/**
 * Nick composer
 *
 * @author Wilhelm Zwertvaegher
 */
class NickComposer implements NickComposerInterface
{
    /**
     * @var array<string, NickComposerRules>
     */
    private array $composerRules;

    /**
     * @param iterable<NickComposerRules> $composerRules
     */
    public function __construct(
        // TODO : could it be interesting to use a locator instead of AutowireIterator ?
        #[AutowireIterator('app.composer_rules')]
        iterable $composerRules,
        private readonly WordFormatterInterface $wordFormatter,
    ) {
        foreach ($composerRules as $rules) {
            $this->composerRules[$rules->getLang()->value] = $rules;
        }
    }

    /**
     *  Build an actual Nick from a Subject, Qualifier, lang and gender
     *  In this case, put words in the right order, delegate words formatting, and applying specific rules if available.
     * @param Subject $subject
     * @param Qualifier $qualifier
     * @param Lang $lang
     * @param WordGender $targetGender
     * @return ComposedNick
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
        $composer = $this->composerRules[$lang->value] ?? null;
        if ($composer) {
            return $composer->apply($composedNick, $targetGender);
        }

        return $composedNick;
    }
}
