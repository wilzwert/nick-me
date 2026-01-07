<?php

namespace App\Service\Formatter\Strategy;

use App\Entity\Word;
use App\Enum\Lang;
use App\Enum\WordGender;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @author Wilhelm Zwertvaegher
 */
#[AutoconfigureTag('app.formatter_strategy')]
class FrenchFormatterStrategy implements FormatterStrategyInterface
{
    public function getLang(): Lang
    {
        return Lang::FR;
    }

    public function format(Word $word, WordGender $targetGender): string
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
}
