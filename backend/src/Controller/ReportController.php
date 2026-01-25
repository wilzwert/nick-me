<?php

namespace App\Controller;

use App\Dto\Command\CreateContactCommand;
use App\Dto\Command\CreateReportCommand;
use App\Dto\Request\ContactRequest;
use App\Dto\Request\ReportRequest;
use App\UseCase\CreateContactInterface;
use App\UseCase\CreateReportInterface;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author Wilhelm Zwertvaegher
 */
#[Route('/api/report')]
class ReportController extends AbstractController
{
    public function __construct(private readonly CreateReportInterface $report)
    {
    }

    #[Route('', name: 'api_report', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] ReportRequest $request): JsonResponse
    {
        $reportCommand = new CreateReportCommand(
            $request->getSenderEmail(),
            $request->getReason(),
            $request->getNickId()
        );
        ($this->report)($reportCommand);

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
