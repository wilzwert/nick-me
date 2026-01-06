<?php

namespace App\UseCase;

use App\Dto\Command\MaintainWordCommand;
use App\Dto\Properties\MaintainQualifierProperties;
use App\Dto\Properties\MaintainWordProperties;
use App\Dto\Response\FullWordDto;
use App\Dto\Response\QualifierDto;
use App\Dto\Response\SubjectDto;
use App\Enum\GrammaticalRoleType;
use App\Service\Data\QualifierServiceInterface;
use App\Service\Data\SubjectServiceInterface;
use App\Service\Data\WordServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
class MaintainWord implements MaintainWordInterface
{
    public function __construct(
        private readonly WordServiceInterface      $wordService,
        private readonly SubjectServiceInterface   $subjectService,
        private readonly QualifierServiceInterface $qualifierService,
        private readonly EntityManagerInterface    $entityManager

    ) {
    }

    public function __invoke(MaintainWordCommand $maintainWordCommand): FullWordDto
    {
        $this->entityManager->beginTransaction();

        // create or update base word
        $spec = new MaintainWordProperties(
            $maintainWordCommand->getLabel(),
            $maintainWordCommand->getGender(),
            $maintainWordCommand->getLang(),
            $maintainWordCommand->getOffenseLevel(),
            $maintainWordCommand->getStatus(),
            $maintainWordCommand->isAsSubject(),
            $maintainWordCommand->isAsQualifier(),
            $maintainWordCommand->getQualifierPosition(),
            $maintainWordCommand->getWordId()
        );
        $word = $this->wordService->createOrUpdate($spec);

        $this->entityManager->flush();

        // handle qualifier and / or subject creation/update or removal
        if ($spec->isAsQualifier()) {
           $qualifier = $this->qualifierService->createOrUpdate(
               $word,
               new MaintainQualifierProperties(
                   $spec->getQualifierPosition()
               )
           );
        }
        else {
            $this->qualifierService->deleteIfExists($word->getId());
        }
        if ($spec->isAsSubject()) {
            $subject = $this->subjectService->createOrUpdate($word);
        }
        else {
            $this->subjectService->deleteIfExists($word->getId());
        }

        // now that all entities have been handled, we can flush the entity manager
        $this->entityManager->flush();
        $this->entityManager->commit();


        // and finally, build and return the Dto
        $types = [];
        if (isset($qualifier)) {
            $types[GrammaticalRoleType::QUALIFIER->value] = new QualifierDto($qualifier->getUsageCount(), $qualifier->getPosition());
        }

        if (isset($subject)) {
            $types[GrammaticalRoleType::SUBJECT->value] = new SubjectDto($subject->getUsageCount());
        }

        return new FullWordDto(
            $word->getId(),
            $word->getLabel(),
            $word->getSlug(),
            $word->getGender(),
            $word->getLang(),
            $word->getOffenseLevel(),
            $word->getStatus(),
            $types
        );
    }
}
