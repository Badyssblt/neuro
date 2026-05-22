<?php

namespace App\MessageHandler;

use App\Message\AskAILessonsMessage;
use App\Service\LearningGoalImporter;
use App\Service\OpenAIService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AskAILessonsHandler
{
    public function __construct(
        private OpenAIService $openAIService,
        private LearningGoalImporter $importer,
    ) {
    }

    public function __invoke(AskAILessonsMessage $message): array
    {
        $data = $this->openAIService->ask($message->getQuestion());
        $this->importer->import($data);

        return $data;
    }
}
