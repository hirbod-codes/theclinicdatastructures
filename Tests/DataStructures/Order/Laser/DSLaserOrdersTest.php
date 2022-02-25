<?php

namespace Tests\DataStructures\Order\Laser;

use Faker\Factory;
use Faker\Generator;
use Mockery;
use Tests\DataStructures\Order\Traits\TraitDSOrdersTests;
use Tests\TestCase;
use TheClinicDataStructures\DataStructures\Order\Laser\DSLaserOrder;
use TheClinicDataStructures\DataStructures\Order\Laser\DSLaserOrders;
use TheClinicDataStructures\DataStructures\User\DSUser;

class DSLaserOrdersTest extends TestCase
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
        $ordersCount = 10;

        $this->testWithRandomUsers($ordersCount);
        $this->testWithOneUser($ordersCount);
    }

    private function testWithRandomUsers(int $ordersCount): void
    {
        $this->testDSOrdersWithRandomUsers(new DSLaserOrders(), DSLaserOrder::class, $ordersCount);
    }

    private function testWithOneUser(int $ordersCount): void
    {
        /** @var \TheClinicDataStructures\DataStructures\User\DSUser|\Mockery\MockInterface $user */
        $user = Mockery::mock(DSUser::class);
        $user->shouldReceive("getId")->andReturn($this->faker->numberBetween(1, 1000));

        $orders = new DSLaserOrders($user);

        $this->testDSOrdersWithOneUser($orders, DSLaserOrder::class, $user, $ordersCount);
    }
}
