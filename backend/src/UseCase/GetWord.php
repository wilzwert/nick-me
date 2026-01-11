<?php

namespace App\UseCase;

use App\Dto\Command\GetWordCommand;
use App\Dto\Response\NickWordDto;
use App\Entity\GrammaticalRole;
use App\Enum\GrammaticalRoleType;
use App\Service\Data\GrammaticalRoleServiceInterface;
use App\Service\Formatter\WordFormatterInterface;
use App\Service\Generator\WordFinderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

/**
 *
 * @author Wilhelm Zwertvaegher
 */
readonly class GetWord implements GetWordInterface
{
    /**
     * @var array<string, GrammaticalRoleServiceInterface<GrammaticalRole>>
     */
    private array $services;

    /**
     * @template T of GrammaticalRole
     * @param iterable<GrammaticalRoleServiceInterface<T>> $services
     */
    public function __construct(
        #[AutowireIterator('app.word_type_data_service')]
        iterable $services,
        private WordFinderInterface $wordFinder,
        private WordFormatterInterface $formatter,
        private EntityManagerInterface $entityManager,
    ) {
        // FIXME : injecting services only to be able to increment usage count seems a bit overkill
        $servicesByWordType = [];
        foreach ($services as $service) {
            $servicesByWordType[$service->getGrammaticalRole()->value] = $service;
        }
        $this->services = $servicesByWordType;
    }

    public function __invoke(GetWordCommand $command): NickWordDto
    {
        $new = $this->wordFinder->findSimilar($command);
        $this->services[$command->getRole()->value]->incrementUsageCount($new);
        $this->entityManager->flush();
        // build the nick word dto
        $targetGender = $command->getGender();

        return new NickWordDto(
            $new->getWord()->getId(),
            $this->formatter->formatLabel($new->getWord(), $targetGender),
            GrammaticalRoleType::fromClass($new::class)
        );
    }
}
