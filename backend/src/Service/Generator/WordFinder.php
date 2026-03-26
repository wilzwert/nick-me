<?php

namespace App\Service\Generator;

use App\Dto\Command\GetWordCommand;
use App\Entity\GrammaticalRole;
use App\Entity\Word;
use App\Enum\GrammaticalRoleType;
use App\Specification\Criteria;
use App\Specification\Criterion\GenderConstraintType;
use App\Specification\Criterion\GenderCriterion;
use App\Specification\Criterion\LangCriterion;
use App\Specification\Criterion\OffenseConstraintType;
use App\Specification\Criterion\OffenseLevelCriterion;
use App\Specification\Criterion\ValuesCriterion;
use App\Specification\Criterion\ValuesCriterionCheck;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class WordFinder implements WordFinderInterface
{
    public function __construct(
        #[AutowireLocator('app.word_type_data_service', indexAttribute: 'index')]
        private ContainerInterface $services,
    ) {
    }

    public function findSimilar(GetWordCommand $command): ?GrammaticalRole
    {
        if (!$this->services->has($command->getRole()->value)) {
            throw new \LogicException('Command with role "'.$command->getRole()->value.'" has no matching GrammaticalRoleServiceInterface.');
        }
        $service = $this->services->get($command->getRole()->value);
        $previous = $command->getPrevious() ?? $service->findByWordId($command->getPreviousId());

        if (null === $previous) {
            throw new \LogicException('Cannot get a word without a previous word');
        }

        $criteria = [new LangCriterion($previous->getWord()->getLang())];
        $criteria[] = new GenderCriterion($command->getGender(), GenderConstraintType::EXACT);
        $criteria[] = new OffenseLevelCriterion(
            $command->getOffenseLevel(),
            GrammaticalRoleType::SUBJECT === $command->getRole() ? OffenseConstraintType::EXACT : OffenseConstraintType::LTE
        );

        if (count($command->getExclusions())) {
            $criteria[] = new ValuesCriterion(Word::class, 'id', $command->getExclusions(), ValuesCriterionCheck::NOT_IN);
        }

        $wordCriteria = new Criteria($criteria);

        return $service->findSimilar($previous, $wordCriteria);
    }
}
