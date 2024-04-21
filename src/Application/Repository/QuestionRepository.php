<?php

declare(strict_types=1);

namespace Application\Repository;

use Application\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class QuestionRepository extends ServiceEntityRepository implements QuestionRepositoryInterface
{

    private string $alias = 'question';

    public function __construct(
        ManagerRegistry $managerRegistry
    ) {
        parent::__construct($managerRegistry, Question::class);
    }

    public function findActiveItems(int $limit = 200): array
    {
        return $this
            ->createQueryBuilder($this->alias)
            ->addSelect('items')
            ->join("$this->alias.items", 'items')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

}
