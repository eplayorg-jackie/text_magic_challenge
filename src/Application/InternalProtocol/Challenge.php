<?php

declare(strict_types=1);

namespace Application\InternalProtocol;

use Application\InternalProtocol\Embedded\ChallengeItem;
use Symfony\Component\Validator\Constraints\Valid;

class Challenge implements PassChallengeInterface
{

    public function __construct(
        /**
         * @var ChallengeItem[] $items
         */
        #[Valid]
        public readonly array $items = []
    ) {
    }

    public function getItems(): array
    {
        return $this->items;
    }

}
