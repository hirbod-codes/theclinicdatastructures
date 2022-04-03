<?php

namespace Tests\DataStructures\Order;

use Faker\Factory;
use Faker\Generator;
use Mockery\MockInterface;
use Tests\DataStructures\Order\Traits\TraitDSOrdersTests;
use Tests\TestCase;
use TheClinicDataStructures\DataStructures\Order\Regular\DSRegularOrder;
use TheClinicDataStructures\DataStructures\User\DSUser;
use TheClinicDataStructures\DataStructures\Visit\DSVisits;

class DSRegularOrderTest extends TestCase
{
    use TraitDSOrdersTests;

    private Generator $faker;

    private DSUser|MockInterface $user;

    private DSVisits|MockInterface|null $visits;

    private int $price;

    private int $neededTime;

    private \DateTime $createdAt;

    private \DateTime $updatedAt;

    private int $id;

    private int $userId;

    private array $constructArgs;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();

        $this->price = $this->faker->numberBetween(100, 600000);
        $this->neededTime = $this->faker->numberBetween(100, 7200);

        $this->visits = null;

        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();

        $this->id = $this->faker->numberBetween(0, 100);
        $this->userId = $this->faker->numberBetween(0, 100);
    }

    public function testDataStructure(): void
    {
        $dsOrder = $this->instantiate();

        $this->assertEquals($dsOrder->getId(), $this->id);
        $this->assertEquals($dsOrder->getUserId(), $this->userId);
        $this->assertEquals($dsOrder->getVisits(), $this->visits);
        $this->assertEquals($dsOrder->getPrice(), $this->price);
        $this->assertEquals($dsOrder->getNeededTime(), $this->neededTime);
        $this->assertEquals($dsOrder->getCreatedAt()->getTimestamp(), $this->createdAt->getTimestamp());
        $this->assertEquals($dsOrder->getUpdatedAt()->getTimestamp(), $this->updatedAt->getTimestamp());
    }

    public function testToArray(): void
    {
        $dsOrderArray = $this->instantiate()->toArray();

        $this->assertIsArray($dsOrderArray);
        $this->assertCount(count($this->constructArgs), $dsOrderArray);

        foreach ($this->constructArgs as $key => $value) {
            $this->assertNotFalse(array_search($key, array_keys($dsOrderArray)));

            if (gettype($value) !== "object") {
                $this->assertEquals($value, $dsOrderArray[$key]);
            } elseif ($value instanceof \DateTime) {
                $this->assertEquals($value->format("Y-m-d H:i:s"), $dsOrderArray[$key]);
            } else {
                $this->assertEquals($value->toArray(), $dsOrderArray[$key]);
            }
        }
    }

    private function instantiate(): DSRegularOrder
    {
        $this->constructArgs = [
            'id' => $this->id,
            'userId' => $this->userId,
            'visits' => $this->visits,
            'price' => $this->price,
            'neededTime' => $this->neededTime,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt
        ];

        return new DSRegularOrder(...$this->constructArgs);
    }
}
