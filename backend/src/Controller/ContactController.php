<?php

namespace App\Controller;

use App\Dto\Command\CreateContactCommand;
use App\Dto\Request\ContactRequest;
use App\UseCase\CreateContactInterface;
use PHPUnit\Util\Json;
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
    public function __construct(private readonly CreateContactInterface $contact)
    {
    }

    #[Route('', name: 'api_contact', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] ContactRequest $request): JsonResponse
    {
        $contactCommand = new CreateContactCommand(
            $request->getSenderEmail(),
            $request->getContent()
        );
        ($this->contact)($contactCommand);

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
