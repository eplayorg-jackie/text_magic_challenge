<?php

declare(strict_types=1);

namespace Application\Entity;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

interface ChallengeInterface
{

    public function getId(): Uuid;

    /**
     * @return Collection<ChallengeItem>
     */
    public function getChallengeItems(): Collection;

    public function markAsPassed(): self;

    public function awaitingPassing(): bool;

    public function asPrepared(): array;

}
