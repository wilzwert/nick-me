<?php

namespace App\Application\UseCase;

use App\Dto\Command\GetWordCommand;
use App\Dto\Response\NickWordDto;
use App\Service\Generator\WordFinderInterface;
use App\Service\Nick\WordFormatterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class GetWord implements GetWordInterface
{
    public function __construct(
        #[AutowireLocator('app.word_type_data_service', indexAttribute: 'index')]
        private ContainerInterface $services,
        private WordFinderInterface $wordFinder,
        private WordFormatterInterface $formatter,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(GetWordCommand $command): NickWordDto
    {
        $new = $this->wordFinder->findSimilar($command);
        if (!$this->services->has($command->getRole()->value)) {
            throw new \LogicException('Command with role "'.$command->getRole()->value.'" has no matching GrammaticalRoleServiceInterface.');
        }
        $this->services->get($command->getRole()->value)->incrementUsageCount($new);
        $this->entityManager->flush();
        // build the nick word dto
        $targetGender = $command->getGender();
        $formattedNickWord = $this->formatter->format($new, $targetGender);

        return new NickWordDto(
            $new->getWord()->getId(),
            $formattedNickWord->label,
            $formattedNickWord->type
        );
    }
}
