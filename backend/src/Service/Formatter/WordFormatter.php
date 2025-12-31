<?php

namespace App\Service\Formatter;

use App\Entity\Word;
use App\Enum\WordGender;
use App\Service\Formatter\Strategy\FormatterStrategyInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

/**
 * @author Wilhelm Zwertvaegher
 */
class WordFormatter implements WordFormatterInterface
{
    /**
     * @var array<string, FormatterStrategyInterface>
     */
    private array $formatters;


    /**
     * @param iterable<FormatterStrategyInterface> $formatters
     */
    public function __construct(
        #[AutowireIterator('app.formatter_strategy')]
        iterable $formatters
    ) {
        foreach ($formatters as $formatter) {
            $this->formatters[$formatter->getLang()->value] = $formatter;
        }
    }

    public function applyCommonFormat(string $label): string
    {
        return ucfirst(strtolower($label));
    }

    public function formatLabel(Word $word, WordGender $gender): string
    {
        return $this->applyCommonFormat(
            isset($this->formatters[$word->getLang()->value]) ?
                $this->formatters[$word->getLang()->value]->format($word, $gender) :
                $word->getLabel()
        );
    }
}
