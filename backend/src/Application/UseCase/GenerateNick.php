<?php

namespace App\Application\UseCase;

use App\Dto\Command\GenerateNickCommand;
use App\Dto\Result\GeneratedNickData;
use App\Service\Data\NickServiceInterface;
use App\Service\Data\QualifierServiceInterface;
use App\Service\Data\SubjectServiceInterface;
use App\Service\Generator\NickGeneratorServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class GenerateNick implements GenerateNickInterface
{
    public function __construct(
        private NickGeneratorServiceInterface $nickGeneratorService,
        private NickServiceInterface $nickService,
        private SubjectServiceInterface $subjectService,
        private QualifierServiceInterface $qualifierService,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(GenerateNickCommand $generateNickCommand): GeneratedNickData
    {
        $nickGenerationResult = $this->nickGeneratorService->generateNick($generateNickCommand);

        $nick = $nickGenerationResult->getNick();

        // increment usages count
        $this->nickService->incrementUsageCount($nick);
        $this->subjectService->incrementUsageCount($nick->getSubject());
        $this->qualifierService->incrementUsageCount($nick->getQualifier());

        $this->entityManager->flush();

        return $nickGenerationResult;
    }
}
