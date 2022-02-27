<?php

namespace TheClinicDataStructures\Tests\DataStructures\Visit\Regular;

use Faker\Factory;
use Faker\Generator;
use Mockery;
use Tests\TestCase;
use TheClinicDataStructures\DataStructures\Order\Regular\DSRegularOrder;
use TheClinicDataStructures\DataStructures\User\DSUser;
use TheClinicDataStructures\DataStructures\Visit\Regular\DSRegularVisit;

class DSRegularVisitTest extends TestCase
{
    private Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function testDataStructure(): void
    {
        $id = $this->faker->numberBetween(1, 1000);

        /** @var \TheClinicDataStructures\DataStructures\User\DSUser|\Mockery\MockInterface $user */
        $user = Mockery::mock(DSUser::class);
        $user->shouldReceive("getId")->andReturn($this->faker->numberBetween(1, 1000));

        /** @var \TheClinicDataStructures\DataStructures\Order\Regular\DSRegularOrder|\Mockery\MockInterface $order */
        $order = Mockery::mock(DSRegularOrder::class);
        $order->shouldReceive("getId")->andReturn($this->faker->numberBetween(1, 1000));

        $visitTimestamp = (new \DateTime())->modify("+1 week")->getTimestamp();

        $consumingTime = $this->faker->numberBetween(600, 3600);

        $createdAt = new \DateTime();
        $updatedAt = new \DateTime();

        $dsVisit = new DSRegularVisit(
            $id,
            $user,
            $order,
            $visitTimestamp,
            $consumingTime,
            null,
            null,
            $createdAt,
            $updatedAt,
        );

        $this->assertEquals($dsVisit->getId(), $id);
        $this->assertEquals($dsVisit->getUser()->getId(), $user->getId());
        $this->assertEquals($dsVisit->getOrder()->getId(), $order->getId());

        $this->assertEquals($dsVisit->getVisitTimestamp(), $visitTimestamp);
        $this->assertEquals($dsVisit->getConsumingTime(), $consumingTime);

        $this->assertEquals($dsVisit->getCreatedAt()->getTimestamp(), $createdAt->getTimestamp());
        $this->assertEquals($dsVisit->getUpdatedAt()->getTimestamp(), $updatedAt->getTimestamp());
    }
}
