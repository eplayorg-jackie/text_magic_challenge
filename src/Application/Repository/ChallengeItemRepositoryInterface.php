<?php

declare(strict_types=1);

namespace Application\Repository;

use Application\Entity\ChallengeInterface;
use Application\Entity\ChallengeItem;
use Application\Entity\Embedded\ChallengeStatus;
use Application\Entity\Embedded\Decision;
use Application\Entity\UserInterface;
use Application\InternalProtocol\Embedded\SelectedChallengeItemInterface;

interface ChallengeItemRepositoryInterface
{

    public function persist(ChallengeItem $challenge): void;

    public function identifyChallengeItem(
        UserInterface $user,
        ChallengeInterface $challenge,
        SelectedChallengeItemInterface $selectedChallengeItem,
        ChallengeStatus $challengeStatus = ChallengeStatus::INITIATED,
        Decision $challengeItemDecision = Decision::INITIATED,
    ): ?ChallengeItem;

}
