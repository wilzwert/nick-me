<?php

namespace App\Service\Nick\Strategy;

use App\Dto\Result\FormattedNickWord;
use App\Entity\GrammaticalRole;
use App\Entity\Word;
use App\Enum\GrammaticalRoleType;
use App\Enum\Lang;
use App\Enum\WordGender;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @author Wilhelm Zwertvaegher
 */
#[AsTaggedItem(index: Lang::FR->value)]
class FrenchWordRules implements WordRules
{
    public function getLang(): Lang
    {
        return Lang::FR;
    }

    private function getLabel(Word $word, WordGender $targetGender): string
    {
        // sadly, we assume that we don't need to do anything when the target gender is not female
        if (WordGender::F != $targetGender) {
            return $word->getLabel();
        }

        if (WordGender::AUTO != $word->getGender()) {
            return $word->getLabel();
        }

        $rules = [
            'eux' => 'euse',
            'ien' => 'ienne',
            'eur' => 'euse',
            'if' => 'ive',
            'et' => 'ette',
            'on' => 'onne',
        ];
        foreach ($rules as $source => $target) {
            if (str_ends_with($word->getLabel(), $source)) {
                return preg_replace("/{$source}$/", $target, $word->getLabel());
            }
        }

        return $word->getLabel().'e';
    }

    public function resolve(GrammaticalRole $grammaticalRole, WordGender $targetGender): FormattedNickWord
    {
        $word = $grammaticalRole->getWord();

        return new FormattedNickWord(
            $grammaticalRole->getWord()->getId(),
            $this->getLabel($word, $targetGender),
            GrammaticalRoleType::fromClass($grammaticalRole::class)
        );
    }
}
