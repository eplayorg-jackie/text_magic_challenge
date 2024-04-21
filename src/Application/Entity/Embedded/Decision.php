<?php

declare(strict_types=1);

namespace Application\Entity\Embedded;

enum Decision: int
{

    case INITIATED = 0;
    case SELECTED = 1;

    public function isRight(): bool
    {
        return $this->value == self::SELECTED->value;
    }

}
