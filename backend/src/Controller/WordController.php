<?php

namespace App\Controller;

use App\Dto\Request\RandomWordRequest;
use App\Dto\Request\RequestFromQuery;
use App\UseCase\GetWordInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author Wilhelm Zwertvaegher
 */
#[Route('/api/word')]
class WordController extends AbstractController
{
    public function __construct(private readonly GetWordInterface $getWord)
    {
    }

    #[Route('', name: 'api_word', methods: ['GET'])]
    public function __invoke(#[RequestFromQuery] RandomWordRequest $request): JsonResponse
    {
        return $this->json(
            ($this->getWord)($request),
            Response::HTTP_OK
        );
    }
}
