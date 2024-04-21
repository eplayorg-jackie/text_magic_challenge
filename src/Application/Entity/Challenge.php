<?php

declare(strict_types=1);

namespace Application\Entity;

use Application\Entity\Embedded\ChallengeStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'challenges')]
class Challenge implements ChallengeInterface
{

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User $user;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $passedAt;

    #[ORM\Column(type: 'integer', enumType: ChallengeStatus::class)]
    private ChallengeStatus $actualStatus;

    /**
     * @var Collection<ChallengeItem>
     */
    #[ORM\OneToMany(targetEntity: ChallengeItem::class, mappedBy: 'challenge', cascade: ['persist'])]
    private Collection $challengeItems;

    public function __construct(
        Uuid $id,
        User $user,
        \DateTimeImmutable $createdAt,
        ChallengeStatus $actualStatus = ChallengeStatus::INITIATED
    ) {
        $this->id = $id;
        $this->user = $user;
        $this->createdAt = $createdAt;
        $this->actualStatus = $actualStatus;

        $this->challengeItems = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getPassedAt(): \DateTimeImmutable
    {
        return $this->passedAt;
    }

    public function setPassedAt(\DateTimeImmutable $passedAt): Challenge
    {
        $this->passedAt = $passedAt;

        return $this;
    }

    public function getActualStatus(): ChallengeStatus
    {
        return $this->actualStatus;
    }

    public function setActualStatus(ChallengeStatus $actualStatus): Challenge
    {
        $this->actualStatus = $actualStatus;

        return $this;
    }

    public function getChallengeItems(): Collection
    {
        return $this->challengeItems;
    }

    public function addChallengeItem(ChallengeItem $challengeItem): Challenge
    {
        $this->challengeItems[] = $challengeItem;

        return $this;
    }

    public function asPrepared(): array
    {
        $questions = [];
        $items = [];

        /** @var ChallengeItem $challengeItem */
        foreach ($this->challengeItems as $challengeItem) {
            $questionId = $challengeItem->getQuestion()->getId()->toRfc4122();

            $questions[$questionId] = $challengeItem->getQuestion();
            $items[$challengeItem->getPosition()] = $challengeItem;
        }

        \shuffle($questions);

        return [
            'id' => $this->id,
            'actualStatus' => $this->actualStatus->value,
            'questions' => \array_values(\array_map(fn(Question $question) => $question->asArray(), $questions)),
            'items' => \array_values(\array_map(fn(ChallengeItem $item) => $item->asInitialized(), $items)),
        ];
    }

    public function markAsPassed(): self
    {
        $this->passedAt = new \DateTimeImmutable();
        $this->actualStatus = ChallengeStatus::PASSED;

        return $this;
    }

    public function awaitingPassing(): bool
    {
        return $this->actualStatus == ChallengeStatus::INITIATED;
    }

    public function asPassed(): array
    {
        return [
            'id' => $this->id,
            'actualStatus' => $this->actualStatus->value,
            'passedAt' => $this->passedAt?->format('c'),
        ];
    }

    public function asExtended(): array
    {
        return [
            'id' => $this->id,
            'actualStatus' => $this->actualStatus->value,
            'passedAt' => $this->passedAt?->format('c'),
            'challengeItems' => \array_map(
                fn(ChallengeItem $challengeItem) => $challengeItem->asInitialized(),
                $this->challengeItems->toArray()
            )
        ];
    }

}
