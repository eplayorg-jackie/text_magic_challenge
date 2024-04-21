<?php

declare(strict_types=1);

namespace Application\Entity;

interface UserInterface
{

    public function getEmail(): string;

    public function asArray(): array;

}
