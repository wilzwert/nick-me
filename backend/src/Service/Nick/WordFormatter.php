<?php

namespace App\Service\Nick;

use App\Dto\Result\FormattedNickWord;
use App\Entity\GrammaticalRole;
use App\Enum\GrammaticalRoleType;
use App\Enum\WordGender;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class WordFormatter implements WordFormatterInterface
{
    public function __construct(
        #[AutowireLocator('app.word_rules', indexAttribute: 'index')]
        private ContainerInterface $wordRules,
    ) {
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
            $this->wordRules->has($word->getLang()->value) ?
                $this->wordRules->get($word->getLang()->value)->resolve($grammaticalRole, $gender) :
                new FormattedNickWord(
                    $word->getId(),
                    $word->getLabel(),
                    GrammaticalRoleType::fromClass($grammaticalRole::class)
                )
        );
    }
}
