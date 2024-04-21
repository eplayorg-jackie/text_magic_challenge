<?php

declare(strict_types=1);

namespace Application\Provider;

use Application\Entity\ChallengeInterface;
use Application\Entity\UserInterface;
use Application\InternalProtocol\PassChallengeInterface;

interface ChallengeProviderInterface
{

    public function activateChallenge(UserInterface $user): ChallengeInterface;

    public function passChallenge(
        UserInterface $user,
        ChallengeInterface $challenge,
        PassChallengeInterface $passed
    ): void;

}

