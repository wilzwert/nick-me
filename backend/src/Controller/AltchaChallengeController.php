<?php

namespace App\Controller;

use App\Security\Service\AltchaServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author Wilhelm Zwertvaegher
 */
class AltchaChallengeController extends AbstractController
{
    public function __construct(private readonly AltchaServiceInterface $altchaService)
    {
    }

    #[Route('/altcha', name: 'altcha', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        return $this->json($this->altchaService->createChallenge());
    }
}
