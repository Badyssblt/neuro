<?php

namespace App\Controller;

use OpenAI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Message\AskAILessonsMessage;

final class GenerateLessonsController extends AbstractController
{
    #[Route('/api/generate', methods: ['POST'], name: 'app_generate_lessons')]
    public function generateLessons(MessageBusInterface $bus): Response
    {
        $bus->dispatch(new AskAILessonsMessage('Génère moi une leçon de français pour un enfant de 10 ans sur le thème de la nature')); 
    }
}
