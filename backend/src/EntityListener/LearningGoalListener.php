<?php

namespace App\EntityListener;

use App\Entity\LearningGoal;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, entity: LearningGoal::class)]
class LearningGoalListener
{
    public function __construct(private readonly Security $security) {}

    public function prePersist(LearningGoal $goal, PrePersistEventArgs $args): void
    {
        if ($goal->getLearner() !== null) {
            return;
        }

        $user = $this->security->getUser();

        if ($user instanceof User) {
            $goal->setLearner($user);
        }
    }
}
