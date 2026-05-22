<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Concepts;
use App\Entity\LearningGoal;
use App\Entity\LearningPath;
use App\Entity\Lessons;
use App\Entity\Modules;
use Doctrine\ORM\EntityManagerInterface;

class LearningGoalImporter
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function import(array $data): LearningGoal
    {
        $now = new \DateTimeImmutable();

        $goal = new LearningGoal();
        $goal->setTitle($data['title'])
            ->setDescription($data['description'] ?? null)
            ->setLevel($data['level'])
            ->setStatus('draft')
            ->setCreatedAt($now)
            ->setUpdatedAt($now);
        $this->em->persist($goal);

        foreach ($data['learning_paths'] ?? [] as $pathData) {
            $path = new LearningPath();
            $path->setTitle($pathData['title'])
                ->setDescription($pathData['description'] ?? null)
                ->setEstimatedDuration((int) $pathData['estimated_duration'])
                ->setCompleted(false)
                ->setCreatedAt($now)
                ->setUpdatedAt($now);
            $goal->addLearningPath($path);
            $this->em->persist($path);

            foreach ($pathData['modules'] ?? [] as $moduleData) {
                $module = new Modules();
                $module->setTitle($moduleData['title'])
                    ->setDescription($moduleData['description'])
                    ->setPosition((int) $moduleData['position'])
                    ->setDifficulty($moduleData['difficulty'])
                    ->setEstimatedDuration((int) $moduleData['estimated_duration'])
                    ->setCreatedAt($now)
                    ->setUpdatedAt($now);
                $path->addModule($module);
                $this->em->persist($module);

                foreach ($moduleData['concepts'] ?? [] as $conceptData) {
                    $concept = new Concepts();
                    $concept->setTitle($conceptData['title'])
                        ->setDescription($conceptData['description'] ?? null)
                        ->setPosition((int) $conceptData['position'])
                        ->setDifficulty($conceptData['difficulty'])
                        ->setImportanceScore((int) $conceptData['importance_score'])
                        ->setEstimatedTime((int) $conceptData['estimated_time'])
                        ->setCreatedAt($now)
                        ->setUpdatedAt($now);
                    $module->addConcept($concept);
                    $this->em->persist($concept);

                    foreach ($conceptData['lessons'] ?? [] as $lessonData) {
                        $lesson = new Lessons();
                        $lesson->setTitle($lessonData['title'])
                            ->setPosition((int) $lessonData['position'])
                            ->setContent($lessonData['content'])
                            ->setSummary($lessonData['summary'])
                            ->setExamples($lessonData['examples'])
                            ->setCreatedAt($now)
                            ->setUpdatedAt($now);
                        $concept->addLesson($lesson);
                        $this->em->persist($lesson);
                    }
                }
            }
        }

        $this->em->flush();

        return $goal;
    }
}
