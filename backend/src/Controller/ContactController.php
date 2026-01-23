<?php

namespace App\Controller;

use App\Dto\Command\ContactCommand;
use App\Dto\Request\ContactRequest;
use App\UseCase\ContactInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author Wilhelm Zwertvaegher
 */
#[Route('/api/contact')]
class ContactController extends AbstractController
{
    public function __construct(private readonly ContactInterface $contact)
    {
    }

    #[Route('', name: 'api_contact', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] ContactRequest $request): JsonResponse
    {
        $contactCommand = new ContactCommand(
            $request->getSenderEmail(),
            $request->getContent()
        );
        ($this->contact)($contactCommand);

        return $this->json(null, Response::HTTP_CREATED);
    }
}
