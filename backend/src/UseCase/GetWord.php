<?php

namespace App\UseCase;

use App\Dto\Request\RandomWordRequest;
use App\Dto\Response\NickWordDto;
use App\Entity\GrammaticalRole;
use App\Entity\Word;
use App\Enum\GrammaticalRoleType;
use App\Service\Data\GrammaticalRoleServiceInterface;
use App\Service\Formatter\WordFormatterInterface;
use App\Specification\Criterion\GenderConstraintType;
use App\Specification\Criterion\GenderCriterion;
use App\Specification\Criterion\OffenseConstraintType;
use App\Specification\Criterion\OffenseLevelCriterion;
use App\Specification\Criterion\ValuesCriterion;
use App\Specification\Criterion\ValuesCriterionCheck;
use App\Specification\WordCriteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

/**
 * @template T of GrammaticalRole
 * @author Wilhelm Zwertvaegher
 */
readonly class GetWord implements GetWordInterface
{
    /**
     * @var array<string, GrammaticalRoleServiceInterface<T>>
     */
    private array $services;

    /**
     * @param iterable<GrammaticalRoleServiceInterface<T>> $services
     * @param WordFormatterInterface $formatter
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        #[AutowireIterator('app.word_type_data_service')]
        iterable                       $services,
        private WordFormatterInterface $formatter,
        private EntityManagerInterface $entityManager
    ) {
        $servicesByWordType = [];
        foreach ($services as $service) {
            $servicesByWordType[$service->getGrammaticalRole()->value] = $service;
        }
        $this->services = $servicesByWordType;
    }

    public function __invoke(RandomWordRequest $request): NickWordDto
    {
        $service = $this->services[$request->getGrammaticalRoleType()->value];

        $previous = $service->findByWordId($request->getPreviousId());

        $criteria = [];
        if ($request->getGender()) {
            $criteria[] = new GenderCriterion($request->getGender(), GenderConstraintType::EXACT);
        }
        if($request->getOffenseLevel()) {
            $criteria[] = new OffenseLevelCriterion(
                $request->getOffenseLevel(),
                ($request->getGrammaticalRoleType() === GrammaticalRoleType::SUBJECT ? OffenseConstraintType::EXACT : OffenseConstraintType::LTE)
            );
        }
        if (count($request->getExclusions())) {
            $criteria[] = new ValuesCriterion(Word::class, 'id', $request->getExclusions(), ValuesCriterionCheck::NOT_IN);
        }

        $wordCriteria = new WordCriteria(
            $previous->getWord()->getLang(),
            $criteria
        );

        $new = $service->findSimilar($previous, $wordCriteria);
        $service->incrementUsageCount($new);
        $this->entityManager->flush();

        // build the nick word dto
        $targetGender = $request->getGender() ?? $new->getWord()->getGender();
        return new NickWordDto(
            $new->getWord()->getId(),
            $this->formatter->formatLabel($new->getWord(), $targetGender),
            GrammaticalRoleType::fromClass($new::class)
        );
    }
}
