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
     * @var array<FormatterStrategyInterface>
     */
    private array $formatters;


    /**
     * @param iterable<FormatterStrategyInterface> $formatters
     */
    public function __construct(
        #[AutowireIterator('app.formatter_strategy')]
        iterable $formatters
    ) {
        $this->formatters = iterator_to_array($formatters);
    }

    public function formatLabel(Word $word, WordGender $gender): string
    {
        foreach ($this->formatters as $formatter) {
            if ($formatter->supports($word)) {
                return $formatter->format($word, $gender);
            }
        }
        return $word->getLabel();
    }
}
