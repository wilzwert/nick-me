<?php

namespace App\Controller;

use App\Application\UseCase\CreateApiKeyInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author Wilhelm Zwertvaegher
 */
#[Route('/api/key')]
class ApiKeyController extends AbstractController
{
    public function __construct(private readonly CreateApiKeyInterface $createApiKey)
    {
    }

    #[Route('', name: 'api_key', methods: ['POST'])]
    public function __invoke(): JsonResponse
    {
        return new JsonResponse(($this->createApiKey)(), Response::HTTP_CREATED);
    }
}
