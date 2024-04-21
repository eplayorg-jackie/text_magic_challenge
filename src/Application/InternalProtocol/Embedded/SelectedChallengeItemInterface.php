<?php

declare(strict_types=1);

namespace Application\InternalProtocol\Embedded;

use Symfony\Component\Uid\Uuid;

interface SelectedChallengeItemInterface
{

    public function getId(): Uuid;

    public function getQuestionId(): Uuid;

}
