<?php

namespace Tests\DataStructures\Order;

use Faker\Factory;
use Faker\Generator;
use Mockery;
use Tests\TestCase;
use TheClinicDataStructure\DataStructures\Order\DSOrder;
use TheClinicDataStructure\DataStructures\User\DSUser;
use TheClinicDataStructure\DataStructures\Visit\DSVisits;

class DSOrderTest extends TestCase
{
    private Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function testDataStructure(): void
    {
        /** @var DSUser|\Mockery\MockInterface $user */
        $user = Mockery::mock(DSUser::class);
        $user->shouldReceive('getId')->andReturn(56);

        $price = $this->faker->numberBetween(100, 600000);
        $time = $this->faker->numberBetween(100, 7200);

        $createdAt = new \DateTime();
        $updatedAt = new \DateTime();

        $id = $this->faker->numberBetween(0, 100);

        $this->runTheAssertions(
            $id,
            $user,
            null,
            $price,
            $time,
            $createdAt,
            $updatedAt
        );
    }

    private function runTheAssertions(
        int $id,
        DSUser $user,
        DSVisits|null $visits = null,
        int $price,
        int $time,
        \DateTime $createdAt,
        \DateTime $updatedAt
    ): void {
        $dsOrder = new DSOrder(
            $id,
            $user,
            $visits = null,
            $price,
            $time,
            $createdAt,
            $updatedAt
        );

        $this->assertEquals($dsOrder->getId(), $id);
        $this->assertEquals($dsOrder->getUser()->getId(), $user->getId());
        $this->assertEquals($dsOrder->visits, $visits);
        $this->assertEquals($dsOrder->getPrice(), $price);
        $this->assertEquals($dsOrder->getNeededTime(), $time);
        $this->assertEquals($dsOrder->getCreatedAt()->getTimestamp(), $createdAt->getTimestamp());
        $this->assertEquals($dsOrder->getUpdatedAt()->getTimestamp(), $updatedAt->getTimestamp());
    }
}
