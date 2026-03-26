<?php

namespace App\Service\Nick\Strategy;

use App\Dto\Result\FormattedNickWord;
use App\Dto\Result\ComposedNick;
use App\Enum\Lang;
use App\Enum\WordGender;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: Lang::FR->value)]
class FrenchNickComposerRules implements NickComposerRules
{
    /**
     * @var array<\Closure>
     */
    private array $rules;

    public function __construct()
    {
        $this->rules = [
            'apostrophe' => function (ComposedNick $words, FormattedNickWord $word, int $key, int $lastKey) {
                if ($key !== $lastKey && preg_match('/^[aeiouyàâäéèêëîïôöùûüÿ]/i', $words->getWords()[$key + 1]->label)) {
                    return new FormattedNickWord(
                        $word->id,
                        preg_replace('/ ([dsl])e$/', " \\1'", $word->label),
                        $word->type,
                        ''
                    );
                }

                return $word;
            },
        ];
    }

    public function getLang(): Lang
    {
        return Lang::FR;
    }

    public function apply(ComposedNick $composedNick, WordGender $targetGender): ComposedNick
    {
        /**
         * @var array<FormattedNickWord> $resultWords
         */
        $resultWords = [];

        $lastKey = array_key_last($composedNick->getWords());
        foreach ($composedNick->getWords() as $key => $word) {
            foreach ($this->rules as $rule) {
                $resultWords[$key] = $rule($composedNick, $word, $key, $lastKey);
            }
        }

        return new ComposedNick(
            $composedNick->getTargetGender(),
            $composedNick->getTargetOffenseLevel(),
            $resultWords
        );
    }
}
