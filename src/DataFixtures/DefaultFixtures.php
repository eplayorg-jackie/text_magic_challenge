<?php

namespace DataFixtures;

use Application\Entity\Item;
use Application\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\Uid\Factory\UuidFactory;

class DefaultFixtures extends Fixture
{

    private const QUESTIONS = [
        '1 + 1' => [
            ['3', false],
            ['2', true],
            ['0', false],
        ],
        '2 + 2' => [
            ['4', true],
            ['3 + 1', true],
            ['10', false],
        ],
        '3 + 3' => [
            ['1 + 5', true],
            ['1', false],
            ['6', true],
            ['2 + 4', true],
        ],
        '4 + 4' => [
            ['8', true],
            ['4', false],
            ['0', false],
            ['0 + 8', true],
        ],
        '5 + 5' => [
            ['6', false],
            ['18', false],
            ['10', true],
            ['9', false],
            ['0', false],
        ],
        '6 + 6' => [
            ['3', false],
            ['9', false],
            ['0', false],
            ['12', true],
            ['5 + 7', true],
        ],
        '7 + 7' => [
            ['5', false],
            ['14', true],
        ],
        '8 + 8' => [
            ['16', true],
            ['12', false],
            ['9', false],
            ['5', false],
        ],
        '9 + 9' => [
            ['18', true],
            ['9', false],
            ['17 + 1', true],
            ['2 + 16', true],
        ],
        '10 + 10' => [
            ['0', false],
            ['2', false],
            ['8', false],
            ['20', true],
        ],
    ];

    public function __construct(
        private readonly UuidFactory $idFactory,
        private readonly ClockInterface $clock,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $now = $this->clock->now();
        foreach (self::QUESTIONS as $definition => $items) {
            $question = new Question($this->idFactory->create(), $definition, $now);
            $manager->persist($question);

            foreach ($items as $item) {
                $item = new Item(
                    $this->idFactory->create(),
                    expression: $item[0],
                    isCorrect: $item[1],
                    question: $question,
                    createdAt: $now,
                );
                $manager->persist($item);
            }
        }

        $manager->flush();
    }

}
