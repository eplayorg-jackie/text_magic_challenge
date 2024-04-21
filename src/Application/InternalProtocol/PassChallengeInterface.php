<?php

declare(strict_types=1);

namespace Application\InternalProtocol;

use Application\InternalProtocol\Embedded\SelectedChallengeItemInterface;

interface PassChallengeInterface
{

    /**
     * @return SelectedChallengeItemInterface[]
     */
    public function getItems(): array;

}
