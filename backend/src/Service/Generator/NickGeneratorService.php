<?php

namespace App\Service\Generator;

use App\Dto\Command\GenerateNickCommand;
use App\Dto\Command\GetWordCommand;
use App\Dto\Result\GeneratedNickData;
use App\Dto\Result\GeneratedNickWord;
use App\Entity\GrammaticalRole;
use App\Entity\Nick;
use App\Entity\Qualifier;
use App\Entity\Subject;
use App\Entity\Word;
use App\Enum\GrammaticalRoleType;
use App\Enum\OffenseLevel;
use App\Enum\QualifierPosition;
use App\Enum\WordGender;
use App\Exception\NickNotFoundException;
use App\Service\Data\NickService;
use App\Service\Data\NickServiceInterface;
use App\Service\Data\QualifierServiceInterface;
use App\Service\Data\SubjectServiceInterface;
use App\Service\Formatter\WordFormatterInterface;
use App\Specification\Criterion\GenderConstraintType;
use App\Specification\Criterion\GenderCriterion;
use App\Specification\Criterion\OffenseConstraintType;
use App\Specification\Criterion\OffenseLevelCriterion;
use App\Specification\Criterion\ValuesCriterion;
use App\Specification\Criterion\ValuesCriterionCheck;
use App\Specification\WordCriteria;
use Random\RandomException;

/**
 * @author Wilhelm Zwertvaegher
 */
class NickGeneratorService implements NickGeneratorServiceInterface
{
    public function __construct(
        private readonly SubjectServiceInterface $subjectService,
        private readonly QualifierServiceInterface $qualifierService,
        private readonly WordFormatterInterface $formatter,
        private readonly NickServiceInterface $nickService,
        private readonly WordFinderInterface $wordFinder
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
     * TL;DR ; a Nick's target Gender cannot be AUTO, it MUST be a defined GENDER.
     *
     * @throws RandomException
     */
    private function computeTargetGender(GenerateNickCommand $command, Subject $subject): WordGender
    {
        // in case a non-auto gender has been explicitly asked, we have to respect it
        if (null !== $command->getGender() && WordGender::AUTO !== $command->getGender()) {
            return $command->getGender();
        }

        // in other cases, Gender depends on the found Subject gender
        return match ($subject->getWord()->getGender()) {
            // neutral is randomly forced to M or F to increase possibilities, otherwise it would be very limited
            // because in some languages neutral words are rare
            // in any case, having a random M or F target gender will still allow NEUTRAL qualifiers
            WordGender::AUTO, WordGender::NEUTRAL => 1 === random_int(0, 1) ? WordGender::M : WordGender::F,
            default => $subject->getWord()->getGender(),
        };
    }

    /**
     * @param Subject $subject
     * @param Qualifier $qualifier
     * @param WordGender $targetGender
     * @return GeneratedNickData
     */
    private function buildGeneratedNick(Subject $subject, Qualifier $qualifier, WordGender $targetGender): GeneratedNickData
    {
        // build the generated nick data
        $words = [
            new GeneratedNickWord(
                $subject->getWord()->getId(),
                $this->formatter->formatLabel($subject->getWord(), $targetGender),
                GrammaticalRoleType::fromClass($subject::class)
            ),
        ];
        $qualifierWord = new GeneratedNickWord(
            $qualifier->getWord()->getId(),
            $this->formatter->formatLabel($qualifier->getWord(), $targetGender),
            GrammaticalRoleType::fromClass($qualifier::class)
        );
        if (QualifierPosition::AFTER === $qualifier->getPosition()) {
            $words[] = $qualifierWord;
        } else {
            array_unshift($words, $qualifierWord);
        }

        $label = implode(' ', array_map(fn (GeneratedNickWord $word) => $word->label, $words));
        $nick = $this->nickService->getOrCreate(
            $subject,
            $qualifier,
            $targetGender,
            $subject->getWord()->getOffenseLevel(),
            $label
        );

        return new GeneratedNickData(
            $nick->getTargetGender(),
            $nick->getOffenseLevel(),
            $nick,
            $words
        );
    }

    /**
     * @throws NickNotFoundException
     */
    private function updateNick(GenerateNickCommand $command): GeneratedNickData
    {
        $previousNick = $this->nickService->getNick($command->getPreviousNickId());
        if ($previousNick === null) {
            throw new NickNotFoundException();
        }

        // create a new nick with a replaced word
        $subject = $previousNick->getSubject();
        $qualifier = $previousNick->getQualifier();

        switch ($command->getReplaceRoleType()) {
            case GrammaticalRoleType::SUBJECT:
                /** @var Subject $subject */
                $subject = $this->wordFinder->findSimilar(
                    new GetWordCommand(
                        GrammaticalRoleType::SUBJECT,
                        // we better trust the previous nick than parameters received
                        $previousNick->getTargetGender(),
                        $previousNick->getOffenseLevel(),
                        null,
                        $subject,
                        $command->getExclusions()
                    )
                );
                assert($subject instanceof Subject);
                break;
            case GrammaticalRoleType::QUALIFIER:
                /** @var Qualifier $qualifier */
                $qualifier = $this->wordFinder->findSimilar(
                    new GetWordCommand(
                        GrammaticalRoleType::QUALIFIER,
                        // we better trust the previous nick than parameters received
                        $previousNick->getTargetGender(),
                        $previousNick->getOffenseLevel(),
                        null,
                        $qualifier,
                        $command->getExclusions()
                    )
                );
                assert($qualifier instanceof Qualifier);
                break;
        }

        return $this->buildGeneratedNick($subject, $qualifier, $previousNick->getTargetGender());
    }

    /**
     * @throws RandomException
     */
    private function createNick(GenerateNickCommand $command): GeneratedNickData
    {
        // get a Subject according to OffenseLevel and Gender
        $criteria = [];
        $criteria[] = new GenderCriterion(
            $command->getGender(),
            GenderConstraintType::EXACT
        );
        $criteria[] = new OffenseLevelCriterion($command->getOffenseLevel(), OffenseConstraintType::EXACT);
        if (count($command->getExclusions())) {
            $criteria = new ValuesCriterion(Word::class, 'id', $command->getExclusions(), ValuesCriterionCheck::NOT_IN);
        }

        $subject = $this->subjectService->findOneRandomly(
            new WordCriteria(
                $command->getLang(),
                $criteria
            )
        );
        $targetGender = $this->computeTargetGender($command, $subject);

        $exclusions = $command->getExclusions();
        $exclusions[] = $subject->getWord()->getId();

        $criteria = [
            new GenderCriterion(
                $targetGender,
                GenderConstraintType::RELAXED,
            ),
            new OffenseLevelCriterion(
                $subject->getWord()->getOffenseLevel(),
                OffenseLevel::MAX === $command->getOffenseLevel() ? OffenseConstraintType::EXACT : OffenseConstraintType::LTE,
            ),
            new ValuesCriterion(Word::class, 'id', $exclusions, ValuesCriterionCheck::NOT_IN),
        ];

        // get a Qualifier according to the Subject's OffenseLevel and Gender
        $qualifier = $this->qualifierService->findOneRandomly(
            new WordCriteria(
                $command->getLang(),
                $criteria
            )
        );

        return $this->buildGeneratedNick($subject, $qualifier, $targetGender);
    }

    public function generateNick(GenerateNickCommand $command): GeneratedNickData
    {
        // create a new Nick, or "update" an existing one
        if ($command->getPreviousNickId()) {
            return $this->updateNick($command);
        }

        return $this->createNick($command);
    }
}
