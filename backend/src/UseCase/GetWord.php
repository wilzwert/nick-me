<?php

namespace App\UseCase;

use App\Dto\Request\RandomNickRequest;
use App\Dto\Request\RandomWordRequest;
use App\Dto\Response\NickDto;
use App\Dto\Response\NickWordDto;
use App\Entity\GrammaticalRole;
use App\Enum\OffenseLevel;
use App\Enum\QualifierPosition;
use App\Enum\GrammaticalRoleType;
use App\Enum\WordGender;
use App\Service\Data\QualifierServiceInterface;
use App\Service\Data\SubjectServiceInterface;
use App\Service\Data\GrammaticalRoleServiceInterface;
use App\Service\Formatter\WordFormatterInterface;
use App\Specification\GenderConstraintType;
use App\Specification\GenderCriterion;
use App\Specification\OffenseConstraintType;
use App\Specification\OffenseLevelCriterion;
use App\Specification\WordCriteria;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

/**
 * @author Wilhelm Zwertvaegher
 */
class GetWord implements GetWordInterface
{
    /**
     * @template T of GrammaticalRole
     * @var array<string, GrammaticalRoleServiceInterface<T>>
     */
    private readonly array $services;

    /**
     * @template T of GrammaticalRole
     * @param iterable<GrammaticalRoleServiceInterface<T>> $services
     * @param WordFormatterInterface $formatter
     */
    public function __construct(
        #[AutowireIterator('app.word_type_data_service')]
        iterable $services,
        private readonly WordFormatterInterface $formatter
    ) {
        $servicesByWordType = [];
        foreach ($services as $service) {
            $servicesByWordType[$service->getGrammaticalRole()->value] = $service;
        }
        $this->services = $servicesByWordType;
    }

    public function __invoke(RandomWordRequest $request): NickWordDto
    {
        $service = $this->services[$request->getGrammaticalRole()->value];

        $previous = $service->findByWordId($request->getPreviousId());

        $criteria = [];
        if ($request->getGender()) {
            $criteria[] = new GenderCriterion($request->getGender(), GenderConstraintType::EXACT);
        }
        if($request->getOffenseLevel()) {
            $criteria[] = new OffenseLevelCriterion(
                $request->getOffenseLevel(),
                ($request->getGrammaticalRole() === GrammaticalRoleType::SUBJECT ? OffenseConstraintType::EXACT : OffenseConstraintType::LTE)
            );
        }
        $wordCriteria = new WordCriteria(
            $previous->getWord()->getLang(),
            $request->getGrammaticalRole(),
            $criteria,
            $request->getExclusions()
        );

        $new = $service->findAnother($previous, $wordCriteria);
        $service->incrementUsageCount($new);

        // build the nick word dto
        $targetGender = $request->getGender() ?? $new->getWord()->getGender();
        return new NickWordDto(
            $new->getWord()->getId(),
            $this->formatter->formatLabel($new->getWord(), $targetGender),
            GrammaticalRoleType::fromClass($new::class)
        );
    }
}
