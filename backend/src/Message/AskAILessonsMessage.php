<?php

namespace App\Message;

class AskAILessonsMessage
{
    public function __construct(private string $question){}

    public function getQuestion(): string
    {
        return $this->question;
    }
} 