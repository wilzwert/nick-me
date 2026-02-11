<?php

namespace App\Service\Nick\Strategy;

use App\Dto\Result\GeneratedNickWord;
use App\Dto\Result\GeneratedNickWords;
use App\Enum\Lang;
use App\Enum\WordGender;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.composer_rules')]
class FrenchNickComposerRules implements NickComposerRules
{
    /**
     * @var array<\Closure>
     */
    private array $rules;

    public function __construct()
    {
        $this->rules = [
            'apostrophe' => function (GeneratedNickWords $words, GeneratedNickWord $word, int $key, int $lastKey) {
                if ($key !== $lastKey && preg_match('/^[aeiouyàâäéèêëîïôöùûüÿ]/i', $words->getWords()[$key + 1]->label)) {
                    return new GeneratedNickWord(
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

    public function apply(GeneratedNickWords $generatedNickWords, WordGender $targetGender): GeneratedNickWords
    {
        /**
         * @var array<GeneratedNickWord> $resultWords
         */
        $resultWords = [];

        $lastKey = array_key_last($generatedNickWords->getWords());
        foreach ($generatedNickWords->getWords() as $key => $word) {
            foreach ($this->rules as $rule) {
                $resultWords[$key] = $rule($generatedNickWords, $word, $key, $lastKey);
            }
        }

        return new GeneratedNickWords(
            $generatedNickWords->getTargetGender(),
            $generatedNickWords->getTargetOffenseLevel(),
            $resultWords
        );
    }
}
