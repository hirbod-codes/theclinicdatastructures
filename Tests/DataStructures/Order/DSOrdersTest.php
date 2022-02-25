<?php

namespace Tests\DataStructures\Order;

use Faker\Factory;
use Faker\Generator;
use Mockery;
use Tests\TestCase;
use TheClinicDataStructures\DataStructures\Order\DSOrder;
use TheClinicDataStructures\DataStructures\Order\DSOrders;
use TheClinicDataStructures\DataStructures\User\DSUser;
use TheClinicDataStructures\Exceptions\DataStructures\Order\OrderExceptions;

class DSOrdersTest extends TestCase
{
    private Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function testDataStructure(): void
    {
        $ordersCount = 5;

        $this->testWithRandomUsers($ordersCount);
        $this->testWithOneUser($ordersCount);
    }

    private function testWithRandomUsers(int $ordersCount): void
    {
        $orders = new DSOrders();

        for ($i = 0; $i < $ordersCount; $i++) {
            /** @var \TheClinicDataStructures\DataStructures\User\DSUser|\Mockery\MockInterface $user */
            $user = Mockery::mock(DSUser::class);
            $user->shouldReceive("getId")->andReturn($this->faker->numberBetween(1, 1000));
            /** @var \TheClinicDataStructures\DataStructures\Order\DSOrder|\Mockery\MockInterface $order */
            $order = Mockery::mock(DSOrder::class);
            $order->shouldReceive("getUser")->andReturn($user);

            $orders[] = $order;
        }

        $this->assertEquals($ordersCount, count($orders));

        // Testing \Iterator Interface
        $counter = 0;

        foreach ($orders as $order) {
            $this->assertInstanceOf(DSOrder::class, $order);

            $counter++;
        }

        $this->assertEquals($ordersCount, $counter);
    }

    private function testWithOneUser(int $ordersCount): void
    {
        /** @var \TheClinicDataStructures\DataStructures\User\DSUser|\Mockery\MockInterface $user */
        $user = Mockery::mock(DSUser::class);
        $user->shouldReceive("getId")->andReturn($this->faker->numberBetween(1, 1000));

        /** @var DSOrders|\Countable $orders */
        $orders = new DSOrders($user);

        for ($i = 0; $i < $ordersCount; $i++) {
            /** @var \TheClinicDataStructures\DataStructures\Order\DSOrder|\Mockery\MockInterface $order */
            $order = Mockery::mock(DSOrder::class);
            $order->shouldReceive("getId")->andReturn($this->faker->numberBetween(1, 1000));
            $order->shouldReceive("getUser")->andReturn($user);

            $orders[] = $order;
        }

        $this->assertEquals($ordersCount, count($orders));

        // Testing \Iterator Interface
        $counter = 0;

        foreach ($orders as $order) {
            $this->assertInstanceOf(DSOrder::class, $order);

            $counter++;
        }

        $this->assertEquals($ordersCount, $counter);

        try {
            /** @var \TheClinicDataStructures\DataStructures\User\DSUser|\Mockery\MockInterface $user */
            $user = Mockery::mock(DSUser::class);
            $user->shouldReceive("getId")->andReturn($this->faker->numberBetween(1, 1000));

            /** @var \TheClinicDataStructures\DataStructures\Order\DSOrder|\Mockery\MockInterface $order */
            $order = Mockery::mock(DSOrder::class);
            $order->shouldReceive("getId")->andReturn($this->faker->numberBetween(1, 1000));
            $order->shouldReceive("getUser")->andReturn($user);

            $orders[] = $order;

            throw new \RuntimeException("You can't add another user's order to this data structure.", 500);
        } catch (OrderExceptions $th) {
        }
    }
}
