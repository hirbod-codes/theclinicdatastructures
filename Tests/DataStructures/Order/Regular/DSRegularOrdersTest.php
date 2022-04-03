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
        $user->id = $this->faker->numberBetween(1, 1000);

        $orders = new DSRegularOrders($user);

        $this->testDSOrdersWithOneUser($orders, DSRegularOrder::class, $user, $ordersCount);
    }

    public function testToArray(): void
    {
        /** @var \TheClinicDataStructures\DataStructures\User\DSUser|\Mockery\MockInterface $user */
        $user = Mockery::mock(DSUser::class);
        $user->shouldReceive("getId")->andReturn($this->faker->numberBetween(1, 1000));
        $user->shouldReceive("toArray")->andReturn(['user']);

        $dsOrders = (new DSRegularOrders($user));
        for ($i = 0; $i < 10; $i++) {
            /** @var \TheClinicDataStructures\DataStructures\Order\DSRegularOrders|\Mockery\MockInterface $dsOrder */
            $dsOrder = Mockery::mock(DSRegularOrder::class);
            $dsOrder->shouldReceive("getId")->andReturn(25);
            $dsOrder->shouldReceive("getUserId")->andReturn($user->getId());
            $dsOrder->shouldReceive("toArray")->andReturn(['order']);

            $dsOrders[] = $dsOrder;
        }

        $dsOrderArray = $dsOrders->toArray();
        $this->assertIsArray($dsOrderArray);
        $this->assertCount(2, $dsOrderArray);

        $this->assertNotFalse(array_search('user', array_keys($dsOrderArray)));
        $this->assertIsArray($dsOrderArray['user']);
        $this->assertCount(1, $dsOrderArray['user']);
        $this->assertEquals('user', $dsOrderArray['user'][0]);

        $this->assertNotFalse(array_search('orders', array_keys($dsOrderArray)));
        $this->assertCount(10, $dsOrderArray['orders']);
        foreach ($dsOrderArray['orders'] as $order) {
            $this->assertIsArray($order);
            $this->assertCount(1, $order);
            $this->assertEquals('order', $order[0]);
        }
    }
}
