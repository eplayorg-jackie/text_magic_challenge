<?php

declare(strict_types=1);

namespace Application\Provider;

use Application\Entity\ChallengeInterface;
use Application\Entity\Embedded\Decision;
use Application\Entity\UserInterface;
use Application\InternalProtocol\PassChallengeInterface;
use Application\Repository\ChallengeItemRepositoryInterface;
use Application\Repository\QuestionRepositoryInterface;
use Application\Service\ChallengeServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Clock\ClockInterface;

class ChallengeProvider implements ChallengeProviderInterface
{

    public function __construct(
        private readonly ClockInterface $clock,
        private readonly EntityManagerInterface $entityManager,
        private readonly ChallengeServiceInterface $challengeService,
        private readonly ChallengeItemRepositoryInterface $challengeItemRepository,
        private readonly QuestionRepositoryInterface $questionRepository,
    ) {
    }

    public function activateChallenge(UserInterface $user): ChallengeInterface
    {
        $this->challengeService->interruptNotPassedItems($user);

        return $this->challengeService->activateItem($user, $this->questionRepository->findActiveItems());
    }

    public function passChallenge(
        UserInterface $user,
        ChallengeInterface $challenge,
        PassChallengeInterface $passed
    ): void {
        $this->entityManager->wrapInTransaction(function () use ($user, $challenge, $passed) {
            $now = $this->clock->now();

            foreach ($challenge->getChallengeItems() as $challengeItem) {
                $challengeItem->setPassedAt($now);
            }

            foreach ($passed->getItems() as $item) {
                $existed = $this->challengeItemRepository->identifyChallengeItem($user, $challenge, $item);
                if (!$existed) {
                    continue;
                }

                $existed
                    ->setPassedAt($now)
                    ->setDecision(Decision::SELECTED);
            }

            $challenge->markAsPassed();
        });
    }

}
