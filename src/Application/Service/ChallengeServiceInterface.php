<?php

declare(strict_types=1);

namespace Application\Service;

use Application\Entity\Challenge;
use Application\Entity\ChallengeInterface;
use Application\Entity\Question;
use Application\Entity\UserInterface;

interface ChallengeServiceInterface
{

    public function interruptNotPassedItems(UserInterface $user): void;

    /**
     * @param UserInterface $user
     * @param Question[] $questions
     * @return ChallengeInterface
     */
    public function activateItem(UserInterface $user, array $questions): ChallengeInterface;

    /**
     * @param UserInterface $user
     * @param int $limit
     * @param int $offset
     * @return Challenge[]
     */
    public function getChallengesByUser(UserInterface $user, int $limit = 0, int $offset = 0): array;

}
