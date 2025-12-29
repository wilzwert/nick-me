<?php

namespace App\UseCase;

use App\Dto\Request\RandomNickRequest;
use App\Dto\Response\NickDto;
use App\Dto\Response\NickWordDto;
use App\Entity\Subject;
use App\Enum\OffenseLevel;
use App\Enum\QualifierPosition;
use App\Enum\GrammaticalRoleType;
use App\Enum\WordGender;
use App\Service\Data\QualifierServiceInterface;
use App\Service\Data\SubjectServiceInterface;
use App\Service\Formatter\WordFormatterInterface;
use App\Specification\GenderConstraintType;
use App\Specification\GenderCriterion;
use App\Specification\OffenseConstraintType;
use App\Specification\OffenseLevelCriterion;
use App\Specification\WordCriteria;
use Random\RandomException;
use function PHPUnit\Framework\callback;

/**
 * @author Wilhelm Zwertvaegher
 */
class GenerateNick implements GenerateNickInterface
{
    public function __construct(
        private readonly SubjectServiceInterface   $subjectService,
        private readonly QualifierServiceInterface $qualifierService,
        private readonly WordFormatterInterface    $formatter
    ) {
    }

    /**
     * After a Subject has randomly been found, we have to set a target Gender for our Nick
     * This is because the /api/word endpoint allows to replace the Subject or the Qualifier of a Nick and we have to produce
     * consistent and compatible words
     * For example :
     * If the user wanted a AUTO nick, got a NEUTRAL subject,
     * and we naively set the target gender as NEUTRAL, it may drastically reduce possibilities. This is ok for a NEUTRAL explicit request,
     * but not an AUTO one.
     * On the other hand we cannot simply set the target as AUTO because in that case for gender-specific words variation we
     * would have to choose a default gender, because reloading the subject or qualifier or a nick would produce gender compatible words
     * And we do not want to choose a gender as default, so by default we will randomly choose between M and F
     * TL;DR ; a Nick's target Gender cannot be AUTO, it MUST be a defined GENDER
     * @param RandomNickRequest $request
     * @param Subject $subject
     * @return WordGender
     * @throws RandomException
     */
    private function setGeneratedGender(RandomNickRequest $request, Subject $subject): WordGender
    {
        // in case a non-auto gender has been explicitly asked, we have to respect it
        if ($request->getGender() !== WordGender::AUTO) {
            return $request->getGender();
        }

        return match ($subject->getWord()->getGender()) {
            WordGender::AUTO, WordGender::NEUTRAL => random_int(0, 1) === 1 ? WordGender::M : WordGender::F,
            default => $subject->getWord()->getGender(),
        };
    }

    public function __invoke(RandomNickRequest $request): NickDto
    {
        // get a Subject according to OffenseLevel and Gender
        $criteria = [];
        if ($request->getGender()) {
            $criteria[] = new GenderCriterion($request->getGender(), GenderConstraintType::EXACT);
        }
        if($request->getOffenseLevel()) {
            $criteria[] = new OffenseLevelCriterion($request->getOffenseLevel(), OffenseConstraintType::EXACT);
        }

        $subject = $this->subjectService->findOneRandomly(
            new WordCriteria(
                $request->getLang(),
                GrammaticalRoleType::SUBJECT,
                $criteria,
                $request->getExclusions()
            )
        );

        $targetGender = $this->setGeneratedGender($request, $subject);

        $exclusions = $request->getExclusions();
        $exclusions[] = $subject->getWord()->getId();

        // get a Qualifier according to the Subject's OffenseLevel and Gender
        $qualifier = $this->qualifierService->findOneRandomly(
            new WordCriteria(
                $request->getLang(),
                GrammaticalRoleType::QUALIFIER,
                [
                    new GenderCriterion(
                        $targetGender,
                        GenderConstraintType::RELAXED,
                    ),
                    new OffenseLevelCriterion(
                        $subject->getWord()->getOffenseLevel(),
                        $request->getOffenseLevel() === OffenseLevel::MAX ? OffenseConstraintType::EXACT : OffenseConstraintType::LTE,
                    )
                ],
                $exclusions
            )
        );

        // build the nick dto
        $words = [
            new NickWordDto(
                $subject->getWord()->getId(),
                $this->formatter->formatLabel($subject->getWord(), $targetGender),
                GrammaticalRoleType::fromClass($subject::class)
            )
        ];
        $qualifierWordDto = new NickWordDto(
            $qualifier->getWord()->getId(),
            $this->formatter->formatLabel($qualifier->getWord(), $targetGender),
            GrammaticalRoleType::fromClass($qualifier::class));
        if($qualifier->getPosition() === QualifierPosition::AFTER) {
            $words[] = $qualifierWordDto;
        }
        else {
            array_unshift($words, $qualifierWordDto);
        }

        return new NickDto($targetGender, $subject->getWord()->getOffenseLevel(), $words);
    }
}
