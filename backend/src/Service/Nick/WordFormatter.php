<?php

namespace App\Service\Nick;

use App\Dto\Result\FormattedNickWord;
use App\Entity\GrammaticalRole;
use App\Enum\GrammaticalRoleType;
use App\Enum\WordGender;
use App\Service\Nick\Strategy\WordRules;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

/**
 * @author Wilhelm Zwertvaegher
 */
class WordFormatter implements WordFormatterInterface
{
    /**
     * @var array<string, WordRules>
     */
    private array $wordRules;

    /**
     * @param iterable<WordRules> $wordRules
     */
    public function __construct(
        #[AutowireIterator('app.word_rules')]
        iterable $wordRules,
    ) {
        foreach ($wordRules as $formatter) {
            $this->wordRules[$formatter->getLang()->value] = $formatter;
        }
    }

    /**
     * Apply common formatting on a GeneratedNickWord.
     */
    private function applyCommonFormat(FormattedNickWord $formattedNickWord): FormattedNickWord
    {
        return new FormattedNickWord(
            $formattedNickWord->id,
            trim(ucfirst(strtolower($formattedNickWord->label))),
            $formattedNickWord->type
        );
    }

    public function format(GrammaticalRole $grammaticalRole, WordGender $gender): FormattedNickWord
    {
        $word = $grammaticalRole->getWord();

        return $this->applyCommonFormat(
            isset($this->wordRules[$word->getLang()->value]) ?
                $this->wordRules[$word->getLang()->value]->resolve($grammaticalRole, $gender) :
                new FormattedNickWord(
                    $word->getId(),
                    $word->getLabel(),
                    GrammaticalRoleType::fromClass($grammaticalRole::class)
                )
        );
    }
}
