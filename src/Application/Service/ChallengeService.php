<?php

declare(strict_types=1);

namespace Application\Service;

use Application\Entity\Challenge;
use Application\Entity\ChallengeInterface;
use Application\Entity\ChallengeItem;
use Application\Entity\Embedded\ChallengeStatus;
use Application\Entity\Item;
use Application\Entity\Question;
use Application\Entity\UserInterface;
use Application\Repository\ChallengeRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\Uid\Factory\UuidFactory;

class ChallengeService implements ChallengeServiceInterface
{

    public function __construct(
        private readonly UuidFactory $idFactory,
        private readonly EntityManagerInterface $entityManager,
        private readonly ClockInterface $clock,
        private readonly ChallengeRepositoryInterface $challengeRepository,
    ) {
    }

    public function interruptNotPassedItems(UserInterface $user): void
    {
        $generator = $this->challengeRepository->findNotPassedChallengesByUser($user);
        $generator->rewind();

        while ($generator->valid()) {
            /** @var Challenge $challenge */
            $challenge = $generator->current();
            $challenge->setActualStatus(ChallengeStatus::INTERRUPTED);

            $this->entityManager->flush();

            $generator->next();
        }
    }

    public function activateItem(UserInterface $user, array $questions): ChallengeInterface
    {
        $challenge = new Challenge($this->idFactory->create(), $user, $this->clock->now());

        $this->entityManager->wrapInTransaction(
            function (EntityManagerInterface $entityManager) use ($challenge, $questions) {
                $entityManager->persist($challenge);

                \array_walk($questions, function ($question) use ($challenge, $entityManager) {
                    /** @var Question $question */
                    $items = $question->getItems();

                    /** @var Item $item */
                    foreach ($items as $item) {
                        $challengeItem = new ChallengeItem(
                            $this->idFactory->create(),
                            $challenge,
                            $question,
                            $item,
                            $this->clock->now(),
                            \random_int(1, 1000),
                            $item->isCorrect(),
                        );

                        $challenge->addChallengeItem($challengeItem);

                        $entityManager->persist($challengeItem);
                    }
                });
            }
        );

        return $challenge;
    }

    public function getChallengesByUser(UserInterface $user, int $limit = 0, int $offset = 0): array
    {
        $items = $this->challengeRepository->findChallengesByUser($user, $limit, $offset);

        return \array_map(fn(Challenge $challenge) => $challenge->asPrepared(), $items);
    }

}