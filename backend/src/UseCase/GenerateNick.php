<?php

namespace App\UseCase;

use App\Dto\Command\GenerateNickCommand;
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
use App\Service\Generator\NickService;
use App\Specification\GenderConstraintType;
use App\Specification\GenderCriterion;
use App\Specification\OffenseConstraintType;
use App\Specification\OffenseLevelCriterion;
use App\Specification\WordCriteria;
use Doctrine\ORM\EntityManagerInterface;
use Random\RandomException;
use function PHPUnit\Framework\callback;

/**
 * @author Wilhelm Zwertvaegher
 */
class GenerateNick implements GenerateNickInterface
{
    public function __construct(
        private readonly NickService               $nickService,
        private readonly SubjectServiceInterface   $subjectService,
        private readonly QualifierServiceInterface $qualifierService,
        private readonly WordFormatterInterface    $formatter,
        private readonly EntityManagerInterface    $entityManager
    ) {
    }

    public function __invoke(RandomNickRequest $request): NickDto
    {

        $generateNickCommand = new GenerateNickCommand(
            $request->getLang(),
            $request->getGender(),
            $request->getOffenseLevel(),
            $request->getExclusions()
        );

        $nickGenerationResult = $this->nickService->generateNick($generateNickCommand);
        $nick = $nickGenerationResult->getNick();
        $targetGender = $nickGenerationResult->getTargetGender();
        $nick->incrementUsageCount();
        $this->nickService->save($nick);

        // increment usages count
        $this->subjectService->incrementUsageCount($nick->getSubject());
        $this->qualifierService->incrementUsageCount($nick->getQualifier());

        $this->entityManager->flush();

        // build the nick dto
        $words = [
            new NickWordDto(
                $nick->getSubject()->getWord()->getId(),
                $this->formatter->formatLabel($nick->getSubject()->getWord(), $targetGender),
                GrammaticalRoleType::fromClass($nick->getSubject()::class)
            )
        ];
        $qualifierWordDto = new NickWordDto(
            $nick->getQualifier()->getWord()->getId(),
            $this->formatter->formatLabel($nick->getQualifier()->getWord(), $targetGender),
            GrammaticalRoleType::fromClass($nick->getQualifier()::class));
        if($nick->getQualifier()->getPosition() === QualifierPosition::AFTER) {
            $words[] = $qualifierWordDto;
        }
        else {
            array_unshift($words, $qualifierWordDto);
        }

        return new NickDto($targetGender, $nick->getSubject()->getWord()->getOffenseLevel(), $words);
    }
}
