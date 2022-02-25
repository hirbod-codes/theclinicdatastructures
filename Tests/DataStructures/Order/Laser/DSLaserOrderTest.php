<?php

namespace Tests\DataStructures\Order\Laser;

use Faker\Factory;
use Faker\Generator;
use Mockery;
use Tests\TestCase;
use TheClinicDataStructures\DataStructures\Order\DSPackages;
use TheClinicDataStructures\DataStructures\Order\DSParts;
use TheClinicDataStructures\DataStructures\Order\Laser\DSLaserOrder;
use TheClinicDataStructures\DataStructures\User\DSUser;
use TheClinicDataStructures\DataStructures\Visit\DSVisits;
use TheClinicDataStructures\Exceptions\DataStructures\Order\OrderExceptions;

class DSLaserOrderTest extends TestCase
{
    private Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function testDataStructure(): void
    {
        $gender = "Male";

        /** @var DSUser|\Mockery\MockInterface $user */
        $user = Mockery::mock(DSUser::class);
        $user->shouldReceive('getGender')->andReturn($gender);
        $user->shouldReceive('getId')->andReturn(56);

        /** @var DSParts|\Mockery\MockInterface $parts */
        $parts = Mockery::mock(DSParts::class);
        $parts->shouldReceive('getGender')->andReturn($gender);

        /** @var DSPackages|\Mockery\MockInterface $packages */
        $packages = Mockery::mock(DSPackages::class);
        $packages->shouldReceive('getGender')->andReturn($gender);

        $price = $this->faker->numberBetween(100, 600000);
        $time = $this->faker->numberBetween(100, 7200);

        $createdAt = new \DateTime();
        $updatedAt = new \DateTime();

        $id = $this->faker->numberBetween(0, 100);

        $this->runTheAssertions(
            $id,
            $user,
            $parts,
            $packages,
            null,
            $price,
            $time,
            $createdAt,
            $updatedAt
        );

        try {
            /** @var DSUser|\Mockery\MockInterface $user */
            $user = Mockery::mock(DSUser::class);
            $user->shouldReceive('getGender')->andReturn("Female");
            $user->shouldReceive('getId')->andReturn(56);

            $this->runTheAssertions(
                $id,
                $user,
                $parts,
                $packages,
                null,
                $price,
                $time,
                $createdAt,
                $updatedAt
            );
        } catch (OrderExceptions $th) {
            /** @var DSUser|\Mockery\MockInterface $user */
            $user = Mockery::mock(DSUser::class);
            $user->shouldReceive('getGender')->andReturn($gender);
            $user->shouldReceive('getId')->andReturn(56);
        }

        try {
            /** @var DSParts|\Mockery\MockInterface $parts */
            $parts = Mockery::mock(DSParts::class);
            $parts->shouldReceive('getGender')->andReturn("Female");

            $this->runTheAssertions(
                $id,
                $user,
                $parts,
                $packages,
                null,
                $price,
                $time,
                $createdAt,
                $updatedAt
            );
        } catch (OrderExceptions $th) {
            /** @var DSParts|\Mockery\MockInterface $parts */
            $parts = Mockery::mock(DSParts::class);
            $parts->shouldReceive('getGender')->andReturn($gender);
        }

        try {
            /** @var DSPackages|\Mockery\MockInterface $packages */
            $packages = Mockery::mock(DSPackages::class);
            $packages->shouldReceive('getGender')->andReturn("Female");

            $this->runTheAssertions(
                $id,
                $user,
                $parts,
                $packages,
                null,
                $price,
                $time,
                $createdAt,
                $updatedAt
            );
        } catch (OrderExceptions $th) {
            /** @var DSPackages|\Mockery\MockInterface $packages */
            $packages = Mockery::mock(DSPackages::class);
            $packages->shouldReceive('getGender')->andReturn($gender);
        }
    }

    private function runTheAssertions(
        int $id,
        DSUser $user,
        DSParts $parts,
        DSPackages $packages,
        DSVisits|null $visits = null,
        int $price,
        int $time,
        \DateTime $createdAt,
        \DateTime $updatedAt
    ): void {
        $dsOrder = new DSLaserOrder(
            $id,
            $user,
            $parts,
            $packages,
            $visits = null,
            $price,
            $time,
            $createdAt,
            $updatedAt
        );

        $this->assertEquals($dsOrder->getId(), $id);
        $this->assertEquals($dsOrder->getUser()->getId(), $user->getId());
        $this->assertEquals($dsOrder->getParts(), $parts);
        $this->assertEquals($dsOrder->getPackages(), $packages);
        $this->assertEquals($dsOrder->getVisits(), $visits);
        $this->assertEquals($dsOrder->getPrice(), $price);
        $this->assertEquals($dsOrder->getNeededTime(), $time);
        $this->assertEquals($dsOrder->getCreatedAt()->getTimestamp(), $createdAt->getTimestamp());
        $this->assertEquals($dsOrder->getUpdatedAt()->getTimestamp(), $updatedAt->getTimestamp());
    }
}
