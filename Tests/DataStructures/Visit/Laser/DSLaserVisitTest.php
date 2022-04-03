<?php

namespace TheClinicDataStructures\Tests\DataStructures\Visit\Laser;

use Faker\Factory;
use Faker\Generator;
use Mockery;
use Tests\TestCase;
use TheClinicDataStructures\DataStructures\Visit\Laser\DSLaserVisit;

class DSLaserVisitTest extends TestCase
{
    private Generator $faker;

    private int $id;

    private int $visitTimestamp;

    private int $consumingTime;

    private \DateTime $createdAt;

    private \DateTime $updatedAt;

    private array $constructArgs;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();

        $this->id = $this->faker->numberBetween(1, 1000);

        $this->visitTimestamp = (new \DateTime())->modify("+1 week")->getTimestamp();

        $this->consumingTime = $this->faker->numberBetween(600, 3600);

        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function testDataStructure(): void
    {
        $dsVisit = $this->instantiate();

        $this->assertEquals($dsVisit->getId(), $this->id);

        $this->assertEquals($dsVisit->getVisitTimestamp(), $this->visitTimestamp);
        $this->assertEquals($dsVisit->getConsumingTime(), $this->consumingTime);

        $this->assertEquals($dsVisit->getCreatedAt()->getTimestamp(), $this->createdAt->getTimestamp());
        $this->assertEquals($dsVisit->getUpdatedAt()->getTimestamp(), $this->updatedAt->getTimestamp());
    }

    private function instantiate(): DSLaserVisit
    {
        $this->constructArgs = [
            'id' => $this->id,
            'visitTimestamp' => $this->visitTimestamp,
            'consumingTime' => $this->consumingTime,
            'weekDaysPeriods' => null,
            'dateTimePeriod' => null,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];

        return new DSLaserVisit(...$this->constructArgs);
    }

    public function testToArray(): void
    {
        $dsVisit = $this->instantiate();

        $dsVisitArray = $dsVisit->toArray();

        $this->assertIsArray($dsVisitArray);
        $this->assertCount(count($this->constructArgs), $dsVisitArray);

        foreach ($this->constructArgs as $key => $value) {
            $this->assertNotFalse(array_search($key, array_keys($dsVisitArray)));

            if (gettype($value) !== "object") {
                $this->assertEquals($value, $dsVisitArray[$key]);
            } elseif ($value instanceof \DateTime) {
                $this->assertEquals($value->format("Y-m-d H:i:s"), $dsVisitArray[$key]);
            } else {
                $this->assertEquals($value->toArray(), $dsVisitArray[$key]);
            }
        }
    }
}
