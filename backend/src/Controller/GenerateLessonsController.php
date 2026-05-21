<?php

namespace App\Controller;

use App\Message\AskAILessonsMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;

final class GenerateLessonsController extends AbstractController
{
    #[Route('/api/generate', methods: ['POST'], name: 'app_generate_lessons')]
    public function generateLessons(MessageBusInterface $bus): Response
    {
        $envelope = $bus->dispatch(new AskAILessonsMessage('Je veux apprendre React'));
        $result = $envelope->last(HandledStamp::class)?->getResult();

        return $this->json($result);
    }
}
