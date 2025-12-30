<?php

namespace App\Service\Generator;

use App\Dto\Command\GenerateNickCommand;
use App\Dto\Request\RandomNickRequest;
use App\Dto\Result\NickGenerationResult;
use App\Entity\Nick;
use App\Entity\Qualifier;
use App\Entity\Subject;
use App\Enum\GrammaticalRoleType;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use App\Service\Data\QualifierServiceInterface;
use App\Service\Data\SubjectServiceInterface;
use App\Specification\GenderConstraintType;
use App\Specification\GenderCriterion;
use App\Specification\OffenseConstraintType;
use App\Specification\OffenseLevelCriterion;
use App\Specification\WordCriteria;
use Random\RandomException;

/**
 * @author Wilhelm Zwertvaegher
 */
class NickService implements NickServiceInterface
{
    public function __construct(
        private readonly SubjectServiceInterface   $subjectService,
        private readonly QualifierServiceInterface $qualifierService
    ){
    }

    private function generateHash(Subject $subject, Qualifier $qualifier): string
    {
        // TODO
        return hash('sha256', '');
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
    private function computeTargetGender(GenerateNickCommand $command, Subject $subject): WordGender
    {
        // in case a non-auto gender has been explicitly asked, we have to respect it
        if ($command->getGender() !== WordGender::AUTO) {
            return $command->getGender();
        }

        return match ($subject->getWord()->getGender()) {
            WordGender::AUTO, WordGender::NEUTRAL => random_int(0, 1) === 1 ? WordGender::M : WordGender::F,
            default => $subject->getWord()->getGender(),
        };
    }


    public function generateNick(GenerateNickCommand $command): NickGenerationResult
    {

        // get a Subject according to OffenseLevel and Gender
        $criteria = [];
        if ($command->getGender() && $command->getGender() !== WordGender::AUTO) {
            $criteria[] = new GenderCriterion(
                $command->getGender(),
                GenderConstraintType::EXACT
            );
        }
        if($command->getOffenseLevel()) {
            $criteria[] = new OffenseLevelCriterion($command->getOffenseLevel(), OffenseConstraintType::EXACT);
        }

        $subject = $this->subjectService->findOneRandomly(
            new WordCriteria(
                $command->getLang(),
                GrammaticalRoleType::SUBJECT,
                $criteria,
                $command->getExclusions()
            )
        );

        $targetGender = $this->computeTargetGender($command, $subject);

        $exclusions = $command->getExclusions();
        $exclusions[] = $subject->getWord()->getId();

        // get a Qualifier according to the Subject's OffenseLevel and Gender
        $qualifier = $this->qualifierService->findOneRandomly(
            new WordCriteria(
                $command->getLang(),
                GrammaticalRoleType::QUALIFIER,
                [
                    new GenderCriterion(
                        $targetGender,
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
        return new NickGenerationResult(
            $targetGender,
            new Nick(
                $this->generateHash($subject, $qualifier),
                'TODOLabel',
                $subject,
                $qualifier
            )
        );
    }

    public function save(Nick $nick): void
    {
        // TODO: Implement save() method.
    }

    public function incrementUsageCount(Nick $nick): void
    {
        $nick->incrementUsageCount();
    }
}
