<?php

namespace Tests\DataStructures\Order\Traits;

use Mockery;
use Mockery\MockInterface;
use ReflectionClass;
use TheClinicDataStructures\DataStructures\Order\DSOrder;
use TheClinicDataStructures\DataStructures\Order\DSOrders;
use TheClinicDataStructures\DataStructures\User\DSUser;
use TheClinicDataStructures\Exceptions\DataStructures\Order\OrderExceptions;

trait TraitDSOrdersTests
{
    private function testDSOrdersWithRandomUsers(DSOrders $dsOrders, string $dsOrderFullname, int $ordersCount): void
    {
        $dsOrderInfo = new ReflectionClass($dsOrderFullname);
        if ($dsOrderFullname !== DSOrder::class && $dsOrderInfo->getParentClass()->getName() !== DSOrder::class) {
            throw new \RuntimeException("\$dsOrder must extend " . DSOrder::class . " .", 500);
        }

        for ($i = 0; $i < $ordersCount; $i++) {
            /** @var \TheClinicDataStructures\DataStructures\User\DSUser|\Mockery\MockInterface $user */
            $user = Mockery::mock(DSUser::class);
            $user->shouldReceive("getId")->andReturn($this->faker->numberBetween(1, 1000));
            /** @var \TheClinicDataStructures\DataStructures\Order\DSOrder|\Mockery\MockInterface $dsOrder */
            $dsOrder = Mockery::mock($dsOrderFullname);
            $dsOrder->shouldReceive("getUserId")->andReturn($user->getId());

            $dsOrders[] = $dsOrder;
        }

        $this->assertEquals($ordersCount, count($dsOrders));

        // Testing \Iterator Interface
        $counter = 0;

        foreach ($dsOrders as $dsOrder) {
            $this->assertInstanceOf(DSOrder::class, $dsOrder);

            $counter++;
        }

        $this->assertEquals($ordersCount, $counter);
    }

    private function testDSOrdersWithOneUser(DSOrders $dsOrders, string $dsOrderFullname, DSUser $user, int $ordersCount)
    {
        $dsOrderInfo = new ReflectionClass($dsOrderFullname);
        if ($dsOrderFullname !== DSOrder::class && $dsOrderInfo->getParentClass()->getName() !== DSOrder::class) {
            throw new \RuntimeException("\$dsOrder must extend " . DSOrder::class . " .", 500);
        }

        for ($i = 0; $i < $ordersCount; $i++) {
            /** @var \TheClinicDataStructures\DataStructures\Order\DSOrder|\Mockery\MockInterface $order */
            $order = Mockery::mock($dsOrderFullname);
            $order->shouldReceive("getUserId")->andReturn($user->getId());

            $dsOrders[] = $order;
        }

        $this->assertEquals($ordersCount, count($dsOrders));

        // Testing \Iterator Interface
        $counter = 0;

        foreach ($dsOrders as $order) {
            $this->assertInstanceOf($dsOrderFullname, $order);

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
            $order->shouldReceive("getUserId")->andReturn($user->getId());

            $dsOrders[] = $order;

            throw new \RuntimeException("You can't add another user's order to this data structure.", 500);
        } catch (OrderExceptions $th) {
        }
    }
}
