<?php

namespace TheClinicDataStructures\Tests\DataStructures\Visit\Regular;

use Faker\Factory;
use Faker\Generator;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;
use TheClinicDataStructures\DataStructures\Order\Regular\DSRegularOrder;
use TheClinicDataStructures\DataStructures\User\DSUser;
use TheClinicDataStructures\DataStructures\Visit\Regular\DSRegularVisit;

class DSRegularVisitTest extends TestCase
{
    private Generator $faker;

    private int $id;

    private DSUser|MockInterface $user;

    private DSRegularOrder|MockInterface $order;

    private int $visitTimestamp;

    private int $consumingTime;

    private \DateTime $createdAt;

    private \DateTime $updatedAt;

    private array $constructArgs;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();

        $this->id = $this->faker->numberBetween(1, 1000);

        /** @var \TheClinicDataStructures\DataStructures\User\DSUser|\Mockery\MockInterface $user */
        $this->user = Mockery::mock(DSUser::class);
        $this->user->shouldReceive("getId")->andReturn($this->faker->numberBetween(1, 1000));
        $this->user->shouldReceive("toArray")->andReturn(['user']);

        /** @var \TheClinicDataStructures\DataStructures\Order\Regular\DSRegularOrder|\Mockery\MockInterface $order */
        $this->order = Mockery::mock(DSRegularOrder::class);
        $this->order->shouldReceive("getId")->andReturn($this->faker->numberBetween(1, 1000));
        $this->order->shouldReceive("toArray")->andReturn(['order']);

        $this->visitTimestamp = (new \DateTime())->modify("+1 week")->getTimestamp();

        $this->consumingTime = $this->faker->numberBetween(600, 3600);

        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function testDataStructure(): void
    {
        $dsVisit = $this->instantiate();

        $this->assertEquals($dsVisit->getId(), $this->id);
        $this->assertEquals($dsVisit->getUser()->getId(), $this->user->getId());
        $this->assertEquals($dsVisit->getOrder()->getId(), $this->order->getId());

        $this->assertEquals($dsVisit->getVisitTimestamp(), $this->visitTimestamp);
        $this->assertEquals($dsVisit->getConsumingTime(), $this->consumingTime);

        $this->assertEquals($dsVisit->getCreatedAt()->getTimestamp(), $this->createdAt->getTimestamp());
        $this->assertEquals($dsVisit->getUpdatedAt()->getTimestamp(), $this->updatedAt->getTimestamp());
    }

    private function instantiate(): DSRegularVisit
    {
        $this->constructArgs = [
            'id' => $this->id,
            'user' => $this->user,
            'order' => $this->order,
            'visitTimestamp' => $this->visitTimestamp,
            'consumingTime' => $this->consumingTime,
            'weekDaysPeriods' => null,
            'dateTimePeriod' => null,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];

        return new DSRegularVisit(...$this->constructArgs);
    }

    public function testToArray(): void
    {
        $dsVisit = $this->instantiate();

        $dsVisitArray = $dsVisit->toArray();

        $this->assertIsArray($dsVisitArray);
        $this->assertCount(count($this->constructArgs), $dsVisitArray);

        foreach ($this->constructArgs as $key => $value) {
            $this->assertNotFalse(array_search($key, array_keys($dsVisitArray)));

            if (gettype($value) !== "object") {
                $this->assertEquals($value, $dsVisitArray[$key]);
            } elseif ($value instanceof \DateTime) {
                $this->assertEquals($value->format("Y-m-d H:i:s"), $dsVisitArray[$key]);
            } else {
                $this->assertEquals($value->toArray(), $dsVisitArray[$key]);
            }
        }
    }
}
