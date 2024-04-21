<?php

declare(strict_types=1);

namespace Application\Repository;

use Application\Entity\Question;

interface QuestionRepositoryInterface
{

    /**
     * @return Question[]
     */
    public function findActiveItems(int $limit = 200): array;

}
