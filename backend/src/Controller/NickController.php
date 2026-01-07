<?php

namespace App\Controller;

use App\Dto\Command\GenerateNickCommand;
use App\Dto\Request\RandomNickRequest;
use App\Dto\Request\RequestFromQuery;
use App\Dto\Response\NickDto;
use App\Dto\Response\NickWordDto;
use App\Dto\Result\GeneratedNickWord;
use App\UseCase\GenerateNickInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
    public function __invoke(#[RequestFromQuery] RandomNickRequest $request): JsonResponse
    {
        $generateNickCommand = new GenerateNickCommand(
            $request->getLang(),
            $request->getGender(),
            $request->getOffenseLevel(),
            $request->getExclusions()
        );
        $generatedNickData = ($this->generateNick)($generateNickCommand);

        return $this->json(
            new NickDto(
                $generatedNickData->getTargetGender(),
                $generatedNickData->getTargetOffenseLevel(),
                array_map(
                    fn (GeneratedNickWord $word) => new NickWordDto(
                        $word->id,
                        $word->label,
                        $word->type
                    ),
                    $generatedNickData->getWords()
                )
            ),
            Response::HTTP_OK
        );
    }
}
