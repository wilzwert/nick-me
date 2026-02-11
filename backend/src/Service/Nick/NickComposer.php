<?php

namespace App\Service\Nick;

use App\Dto\Result\GeneratedNickWord;
use App\Dto\Result\GeneratedNickWords;
use App\Entity\GrammaticalRole;
use App\Entity\Qualifier;
use App\Entity\Subject;
use App\Enum\Lang;
use App\Enum\QualifierPosition;
use App\Enum\WordGender;
use App\Service\Nick\Strategy\NickComposerRules;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

/**
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
        #[AutowireIterator('app.composer_rules')]
        iterable $composerRules,
        private readonly WordFormatterInterface $wordFormatter,
    ) {
        foreach ($composerRules as $rules) {
            $this->composerRules[$rules->getLang()->value] = $rules;
        }
    }

    public function compose(Subject $subject, Qualifier $qualifier, Lang $lang, WordGender $targetGender): GeneratedNickWords
    {
        // put grammatical roles in the right order
        $grammaticalRoles = [$subject];
        if (QualifierPosition::AFTER === $qualifier->getPosition()) {
            $grammaticalRoles[] = $qualifier;
        } else {
            array_unshift($grammaticalRoles, $qualifier);
        }

        // format words
        $generatedWords = array_map(
            fn (GrammaticalRole $grammaticalRole) => $this->wordFormatter->format($grammaticalRole, $targetGender),
            $grammaticalRoles
        );

        // apply rules if available
        $composer = $this->composerRules[$lang->value] ?? null;
        if ($composer) {
            return $composer->apply(
                new GeneratedNickWords(
                    $targetGender,
                    $subject->getWord()->getOffenseLevel(),
                    $generatedWords
                ),
                $targetGender
            );
        }

        return new GeneratedNickWords(
            $targetGender,
            $subject->getWord()->getOffenseLevel(),
            $generatedWords
        );
    }
}
