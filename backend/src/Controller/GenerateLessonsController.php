<?php

namespace App\Controller;

use App\Message\AskAILessonsMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;

final class GenerateLessonsController extends AbstractController
{
    #[Route('/api/generate', methods: ['POST'], name: 'app_generate_lessons')]
    public function generateLessons(Request $request, MessageBusInterface $bus): JsonResponse
    {
        $topic = trim((string) ($request->toArray()['topic'] ?? ''));

        if ($topic === '') {
            return $this->json(
                ['errors' => ['topic' => ['Le champ topic est requis.']]],
                Response::HTTP_UNPROCESSABLE_ENTITY,
            );
        }

        $envelope = $bus->dispatch(new AskAILessonsMessage($topic));
        $result = $envelope->last(HandledStamp::class)?->getResult();

        return $this->json($result, Response::HTTP_CREATED);
    }
}
