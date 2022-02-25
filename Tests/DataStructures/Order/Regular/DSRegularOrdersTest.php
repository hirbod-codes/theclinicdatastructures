<?php

namespace Tests\DataStructures\Order;

use Faker\Factory;
use Faker\Generator;
use Mockery;
use Tests\DataStructures\Order\Traits\TraitDSOrdersTests;
use Tests\TestCase;
use TheClinicDataStructures\DataStructures\Order\Regular\DSRegularOrder;
use TheClinicDataStructures\DataStructures\Order\Regular\DSRegularOrders;
use TheClinicDataStructures\DataStructures\User\DSUser;

class DSRegularOrdersTest extends TestCase
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
        $this->testDSOrdersWithRandomUsers(new DSRegularOrders(), DSRegularOrder::class, $ordersCount);
    }

    private function testWithOneUser(int $ordersCount): void
    {
        /** @var \TheClinicDataStructures\DataStructures\User\DSUser|\Mockery\MockInterface $user */
        $user = Mockery::mock(DSUser::class);
        $user->shouldReceive("getId")->andReturn($this->faker->numberBetween(1, 1000));

        $orders = new DSRegularOrders($user);

        $this->testDSOrdersWithOneUser($orders, DSRegularOrder::class, $user, $ordersCount);
    }
}
