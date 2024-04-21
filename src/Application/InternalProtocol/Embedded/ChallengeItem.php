<?php

declare(strict_types=1);

namespace Application\InternalProtocol\Embedded;

use Symfony\Component\Uid\Uuid;

class ChallengeItem implements SelectedChallengeItemInterface
{

    public function __construct(
        public readonly Uuid $id,
        public readonly Uuid $questionId
    ) {
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getQuestionId(): Uuid
    {
        return $this->questionId;
    }

}
