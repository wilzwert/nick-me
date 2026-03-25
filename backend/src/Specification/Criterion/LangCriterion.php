<?php

namespace App\Specification\Criterion;

use App\Entity\Word;
use App\Enum\Lang;

class LangCriterion extends ValueCriterion
{
    public function __construct(Lang $lang)
    {
        parent::__construct(Word::class, 'lang', $lang, ValueCriterionCheck::EQ);
    }
}
