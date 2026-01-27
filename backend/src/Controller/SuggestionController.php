<?php

namespace App\Controller;

use App\Application\UseCase\CreateSuggestionInterface;
use App\Dto\Command\CreateSuggestionCommand;
use App\Dto\Request\SuggestionRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author Wilhelm Zwertvaegher
 */
#[Route('/api/suggestion')]
class SuggestionController extends AbstractController
{
    public function __construct(private readonly CreateSuggestionInterface $suggestion)
    {
    }

    #[Route('', name: 'api_suggestion', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] SuggestionRequest $request): JsonResponse
    {
        $suggestionCommand = new CreateSuggestionCommand(
            label: $request->getLabel(),
            senderEmail: $request->getSenderEmail()
        );
        ($this->suggestion)($suggestionCommand);

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
