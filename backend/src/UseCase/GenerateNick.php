<?php

namespace App\UseCase;

use App\Dto\Request\RandomWordRequest;
use App\Dto\Response\NickDto;
use App\Dto\Response\NickWordDto;
use App\Enum\OffenseLevel;
use App\Enum\QualifierPosition;
use App\Enum\WordType;
use App\Service\Data\QualifierServiceInterface;
use App\Service\Data\SubjectServiceInterface;
use App\Service\Formatter\WordFormatterInterface;
use App\Specification\GenderConstraintType;
use App\Specification\GenderCriterion;
use App\Specification\OffenseConstraintType;
use App\Specification\OffenseLevelCriterion;
use App\Specification\WordCriteria;

/**
 * @author Wilhelm Zwertvaegher
 */
class GenerateNick implements GenerateNickInterface
{
    public function __construct(
        private readonly SubjectServiceInterface $subjectService,
        private readonly QualifierServiceInterface $qualifierService,
        private readonly WordFormatterInterface $formatter
    ) {
    }

    public function __invoke(RandomWordRequest $command): NickDto
    {
        // get a Subject according to OffenseLevel and Gender
        $criteria = [];
        if ($command->getGender()) {
            $criteria[] = new GenderCriterion($command->getGender(), GenderConstraintType::EXACT);
        }
        if($command->getOffenseLevel()) {
            $criteria[] = new OffenseLevelCriterion($command->getOffenseLevel(), OffenseConstraintType::EXACT);
        }
        $subject = $this->subjectService->findOneRandomly(
            new WordCriteria(
                $command->getLang(),
                WordType::SUBJECT,
                $criteria,
                $command->getExclusions()
            )
        );

        $exclusions = $command->getExclusions();
        $exclusions[] = $subject->getWord()->getId();

        // get a Qualifier according to the Subject's OffenseLevel and Gender
        $qualifier = $this->qualifierService->findOneRandomly(
            new WordCriteria(
                $command->getLang(),
                WordType::QUALIFIER,
                [
                    new GenderCriterion(
                        $subject->getWord()->getGender(),
                        GenderConstraintType::RELAXED,
                    ),
                    new OffenseLevelCriterion(
                        $subject->getWord()->getOffenseLevel(),
                        $command->getOffenseLevel() === OffenseLevel::MAX ? OffenseConstraintType::EXACT : OffenseConstraintType::LTE,
                    )
                ],
                $exclusions
            )
        );

        // build the nick dto
        $targetGender = $command->getGender() ?? $subject->getWord()->getGender();
        $words = [
            new NickWordDto(
                $subject->getWord()->getId(),
                $this->formatter->formatLabel($subject->getWord(), $targetGender),
                WordType::fromClass($subject::class)
            )
        ];
        $qualifierWordDto = new NickWordDto(
            $qualifier->getWord()->getId(),
            $this->formatter->formatLabel($qualifier->getWord(), $targetGender),
            WordType::fromClass($qualifier::class));
        if($qualifier->getPosition() === QualifierPosition::AFTER) {
            $words[] = $qualifierWordDto;
        }
        else {
            array_unshift($words, $qualifierWordDto);
        }

        return new NickDto($subject->getWord()->getOffenseLevel(), $words);
    }
}
