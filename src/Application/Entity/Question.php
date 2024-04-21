<?php

declare(strict_types=1);

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'questions')]
class Question
{

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $definition;

    /**
     * @var Collection<Item>
     */
    #[ORM\OneToMany(targetEntity: Item::class, mappedBy: 'question', cascade: ['persist'])]
    private Collection $items;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private bool $isActive;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt;

    public function __construct(
        Uuid $id,
        string $definition,
        \DateTimeImmutable $createdAt,
        bool $isActive = true,
    ) {
        $this->id = $id;
        $this->definition = $definition;
        $this->createdAt = $createdAt;
        $this->isActive = $isActive;

        $this->items = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getDefinition(): string
    {
        return $this->definition;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): Question
    {
        $this->items[] = $item;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): Question
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function asArray(): array
    {
        return [
            'id' => $this->id,
            'definition' => $this->definition,
        ];
    }

}
