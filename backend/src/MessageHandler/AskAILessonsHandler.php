<?php

namespace App\MessageHandler;

use App\Message\AskAILessonsMessage;
use App\Service\OpenAIService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;


#[AsMessageHandler]
class AskAILessonsHandler {

    public function __construct(private OpenAIService $openAIService)
    {
    }

    public function __invoke(AskAILessonsMessage $message)
    {
        dump($this->openAIService->ask($message->getQuestion()));
    }
}