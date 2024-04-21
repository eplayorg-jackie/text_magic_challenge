<?php

declare(strict_types=1);

namespace Application\Entity;

use Application\Entity\Embedded\Decision;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'challenge_items')]
class ChallengeItem
{

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Challenge::class, cascade: ['persist'], inversedBy: 'challengeItems')]
    #[JoinColumn(name: 'challenge_id', referencedColumnName: 'id')]
    private Challenge $challenge;

    #[ORM\ManyToOne(targetEntity: Question::class)]
    private Question $question;

    #[ORM\ManyToOne(targetEntity: Item::class)]
    private Item $item;

    #[ORM\Column(type: 'integer')]
    private int $position;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private bool $isCorrect;

    #[ORM\Column(type: 'integer', enumType: Decision::class)]
    private Decision $decision;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $passedAt;

    /**
     * @param Uuid $id
     * @param Challenge $challenge
     * @param Question $question
     * @param Item $item
     * @param \DateTimeImmutable $createdAt
     * @param int $position
     * @param bool $isCorrect
     * @param Decision $decision
     */
    public function __construct(
        Uuid $id,
        Challenge $challenge,
        Question $question,
        Item $item,
        \DateTimeImmutable $createdAt,
        int $position,
        bool $isCorrect = false,
        Decision $decision = Decision::INITIATED,
    )
    {
        $this->id = $id;
        $this->challenge = $challenge;
        $this->question = $question;
        $this->item = $item;
        $this->createdAt = $createdAt;
        $this->position = $position;
        $this->isCorrect = $isCorrect;
        $this->decision = $decision;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getChallenge(): Challenge
    {
        return $this->challenge;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function getItem(): Item
    {
        return $this->item;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    public function getDecision(): Decision
    {
        return $this->decision;
    }

    /**
     * @param Decision $decision
     * @return ChallengeItem
     */
    public function setDecision(Decision $decision): ChallengeItem
    {
        $this->decision = $decision;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getPassedAt(): ?\DateTimeImmutable
    {
        return $this->passedAt;
    }

    public function setPassedAt(?\DateTimeImmutable $passedAt): ChallengeItem
    {
        $this->passedAt = $passedAt;

        return $this;
    }

    public function asInitialized(): array
    {
        return [
            'id' => $this->id,
            'questionId' => $this->question->getId(),
            'expression' => $this->item->getExpression(),
            'position' => $this->position,
            'isRight' => $this->isCorrect && $this->decision->isRight(),
        ];
    }

}
