<?php

namespace App\UseCase;

use App\Dto\Request\RandomWordRequest;
use App\Dto\Response\NickDto;
use App\Dto\Response\NickWordDto;
use App\Enum\QualifierPosition;
use App\Enum\WordType;
use App\Service\Data\QualifierServiceInterface;
use App\Service\Data\SubjectServiceInterface;
use App\Service\Formatter\WordFormatterInterface;
use App\Specification\GenderConstraintType;
use App\Specification\GenderCriteria;
use App\Specification\OffenseConstraintType;
use App\Specification\OffenseLevelCriteria;
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
            $criteria[] = new GenderCriteria($command->getGender(), GenderConstraintType::EXACT);
        }
        if($command->getOffenseLevel()) {
            $criteria[] = new OffenseLevelCriteria($command->getOffenseLevel(), OffenseConstraintType::EXACT);
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
                    new GenderCriteria(
                        $subject->getWord()->getGender(),
                        GenderConstraintType::RELAXED,
                    ),
                    new OffenseLevelCriteria(
                        $subject->getWord()->getOffenseLevel(),
                        OffenseConstraintType::LTE,
                    )
                ],
                $exclusions
            )
        );

        // build the nick dto
        $words = [
            new NickWordDto($subject->getWord()->getId(), $this->formatter->formatLabel($subject->getWord(), $subject->getWord()->getGender()), WordType::fromClass($subject::class))
        ];
        $qualifierWordDto = new NickWordDto(
            $qualifier->getWord()->getId(),
            $this->formatter->formatLabel($qualifier->getWord(), $qualifier->getWord()->getGender()),
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
