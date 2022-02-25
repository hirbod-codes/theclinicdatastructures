<?php

namespace Tests\DataStructures\Order;

use Faker\Factory;
use Faker\Generator;
use Mockery;
use Tests\DataStructures\Order\Traits\TraitDSOrdersTests;
use Tests\TestCase;
use TheClinicDataStructures\DataStructures\Order\Regular\DSRegularOrder;
use TheClinicDataStructures\DataStructures\User\DSUser;
use TheClinicDataStructures\DataStructures\Visit\DSVisits;

class DSRegularOrderTest extends TestCase
{
    use TraitDSOrdersTests;

    private Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function testDataStructure(): void
    {
        /** @var \TheClinicDataStructures\DataStructures\User\DSUser|\Mockery\MockInterface $user */
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
        $dsOrder = new DSRegularOrder(
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
        $this->assertEquals($dsOrder->getVisits(), $visits);
        $this->assertEquals($dsOrder->getPrice(), $price);
        $this->assertEquals($dsOrder->getNeededTime(), $time);
        $this->assertEquals($dsOrder->getCreatedAt()->getTimestamp(), $createdAt->getTimestamp());
        $this->assertEquals($dsOrder->getUpdatedAt()->getTimestamp(), $updatedAt->getTimestamp());
    }
}
