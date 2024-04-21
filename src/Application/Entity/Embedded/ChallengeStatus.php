<?php

declare(strict_types=1);

namespace Application\Entity\Embedded;

enum ChallengeStatus: int
{

    case INITIATED = 0;
    case PASSED = 1;
    case INTERRUPTED = 2;

}
