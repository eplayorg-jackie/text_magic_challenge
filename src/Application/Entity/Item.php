<?php

declare(strict_types=1);

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'items')]
class Item
{

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    #[ORM\Column(type: 'string', length: 20)]
    private string $expression;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private bool $isCorrect;

    #[ORM\ManyToOne(targetEntity: Question::class, cascade: ['persist'], inversedBy: 'items')]
    #[ORM\JoinColumn(name: 'question_id', referencedColumnName: 'id')]
    private Question $question;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    /**
     * @param Uuid $id
     * @param string $expression
     * @param bool $isCorrect
     * @param Question $question
     * @param \DateTimeImmutable $createdAt
     */
    public function __construct(
        Uuid $id,
        string $expression,
        bool $isCorrect,
        Question $question,
        \DateTimeImmutable $createdAt,
    ) {
        $this->id = $id;
        $this->expression = $expression;
        $this->isCorrect = $isCorrect;
        $this->question = $question;
        $this->createdAt = $createdAt;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getExpression(): string
    {
        return $this->expression;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

}
