<?php

namespace App\Service\Generator;

use App\Dto\Command\GetWordCommand;
use App\Entity\GrammaticalRole;
use App\Entity\Word;
use App\Enum\GrammaticalRoleType;
use App\Exception\NoWordFoundException;
use App\Service\Data\GrammaticalRoleServiceInterface;
use App\Specification\Criterion\GenderConstraintType;
use App\Specification\Criterion\GenderCriterion;
use App\Specification\Criterion\OffenseConstraintType;
use App\Specification\Criterion\OffenseLevelCriterion;
use App\Specification\Criterion\ValuesCriterion;
use App\Specification\Criterion\ValuesCriterionCheck;
use App\Specification\WordCriteria;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

/**
 * @author Wilhelm Zwertvaegher
 */
class WordFinder implements WordFinderInterface
{
    /**
     * @var array<string, GrammaticalRoleServiceInterface<GrammaticalRole>>
     */
    private array $services;

    /**
     * @template T of GrammaticalRole
     *
     * @param iterable<GrammaticalRoleServiceInterface<T>> $services
     */
    public function __construct(
        #[AutowireIterator('app.word_type_data_service')]
        iterable $services,
    ) {
        $servicesByWordType = [];
        foreach ($services as $service) {
            $servicesByWordType[$service->getGrammaticalRole()->value] = $service;
        }
        $this->services = $servicesByWordType;
    }

    public function findSimilar(GetWordCommand $command): ?GrammaticalRole
    {
        $service = $this->services[$command->getRole()->value];
        $previous = $command->getPrevious() ?? $service->findByWordId($command->getPreviousId());

        if (null === $previous) {
            throw new \LogicException('Cannot get a word without a previous word');
        }

        $criteria = [];
        $criteria[] = new GenderCriterion($command->getGender(), GenderConstraintType::EXACT);
        $criteria[] = new OffenseLevelCriterion(
            $command->getOffenseLevel(),
            GrammaticalRoleType::SUBJECT === $command->getRole() ? OffenseConstraintType::EXACT : OffenseConstraintType::LTE
        );

        if (count($command->getExclusions())) {
            $criteria[] = new ValuesCriterion(Word::class, 'id', $command->getExclusions(), ValuesCriterionCheck::NOT_IN);
        }

        $wordCriteria = new WordCriteria(
            $previous->getWord()->getLang(),
            $criteria
        );

        return $service->findSimilar($previous, $wordCriteria);
    }
}
