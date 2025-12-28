<?php

namespace App\Controller;

use App\Dto\Request\RandomWordRequest;
use App\Dto\Response\NickDto;
use App\UseCase\GenerateNickInterface;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author Wilhelm Zwertvaegher
 */
#[Route('/api/nick')]
class NickController extends AbstractController
{
    public function __construct(private readonly GenerateNickInterface $generateNick)
    {
    }

    #[Route('', name: 'api_nick', methods: ['GET'])]
    public function __invoke(
        #[MapQueryString]
        RandomWordRequest $request,
    ): JsonResponse
    {
        return $this->json(
            ($this->generateNick)($request),
            Response::HTTP_OK
        );
    }

}
