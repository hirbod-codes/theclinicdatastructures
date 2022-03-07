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

    public function testToArray(): void
    {
        /** @var \TheClinicDataStructures\DataStructures\User\DSUser|\Mockery\MockInterface $user */
        $user = Mockery::mock(DSUser::class);
        $user->shouldReceive("getId")->andReturn($this->faker->numberBetween(1, 1000));
        $user->shouldReceive("toArray")->andReturn(['user']);

        $dsOrders = (new DSLaserOrders($user));
        for ($i = 0; $i < 10; $i++) {
            /** @var \TheClinicDataStructures\DataStructures\Order\DSLaserOrders|\Mockery\MockInterface $dsOrder */
            $dsOrder = Mockery::mock(DSLaserOrder::class);
            $dsOrder->shouldReceive("getId")->andReturn(25);
            $dsOrder->shouldReceive("getUser")->andReturn($user);
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
