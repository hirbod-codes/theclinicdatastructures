<?php

namespace Tests\DataStructures\Order\Laser;

use Faker\Factory;
use Faker\Generator;
use Mockery;
use Mockery\MockInterface;
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

    private int $id;

    private DSUser|MockInterface $user;

    private DSParts|MockInterface $parts;

    private DSPackages|MockInterface $packages;

    private DSVisits|MockInterface|null $visits;

    private int $price;

    private int $neededTime;

    private \DateTime $createdAt;

    private \DateTime $updatedAt;

    private array $constructArgs;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();

        $this->gender = "Male";

        $this->user = $this->mockUser($this->gender);

        $this->parts = $this->mockParts($this->gender);

        $this->packages = $this->mockPackages($this->gender);

        $this->visits = null;

        $this->price = $this->faker->numberBetween(100, 600000);
        $this->neededTime = $this->faker->numberBetween(100, 7200);

        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();

        $this->id = $this->faker->numberBetween(0, 100);
    }

    private function mockUser(string $gender): DSUser|MockInterface
    {
        /** @var DSUser|\Mockery\MockInterface $user */
        $user = Mockery::mock(DSUser::class);
        $user->shouldReceive('getGender')->andReturn($gender);
        $user->shouldReceive('getId')->andReturn(56);
        $user->shouldReceive('toArray')->andReturn(['user']);

        return $user;
    }

    private function mockParts(string $gender): DSParts|MockInterface
    {
        /** @var DSParts|\Mockery\MockInterface $parts */
        $parts = Mockery::mock(DSParts::class);
        $parts->shouldReceive('getGender')->andReturn($gender);
        $parts->shouldReceive('toArray')->andReturn(['parts']);

        return $parts;
    }

    private function mockPackages(string $gender): DSPackages|MockInterface
    {
        /** @var DSPackages|\Mockery\MockInterface $packages */
        $packages = Mockery::mock(DSPackages::class);
        $packages->shouldReceive('getGender')->andReturn($gender);
        $packages->shouldReceive('toArray')->andReturn(['packages']);

        return $packages;
    }

    public function testDataStructure(): void
    {
        $this->runTheAssertions();

        try {
            $this->user = $this->mockUser("Female");

            $this->runTheAssertions();
        } catch (OrderExceptions $th) {
            $this->user = $this->mockUser($this->gender);
        }

        try {
            $this->parts = $this->mockParts("Female");

            $this->runTheAssertions();
        } catch (OrderExceptions $th) {
            $this->parts = $this->mockParts($this->gender);
        }

        try {
            $this->packages = $this->mockPackages("Female");

            $this->runTheAssertions();
        } catch (OrderExceptions $th) {
            $this->packages = $this->mockPackages($this->gender);
        }
    }

    private function runTheAssertions(): void
    {
        $dsOrder = $this->instantiate();

        $this->assertEquals($dsOrder->getId(), $this->id);
        $this->assertEquals($dsOrder->getUser()->getId(), $this->user->getId());
        $this->assertEquals($dsOrder->getParts(), $this->parts);
        $this->assertEquals($dsOrder->getPackages(), $this->packages);
        $this->assertEquals($dsOrder->getVisits(), $this->visits);
        $this->assertEquals($dsOrder->getPrice(), $this->price);
        $this->assertEquals($dsOrder->getNeededTime(), $this->neededTime);
        $this->assertEquals($dsOrder->getCreatedAt()->getTimestamp(), $this->createdAt->getTimestamp());
        $this->assertEquals($dsOrder->getUpdatedAt()->getTimestamp(), $this->updatedAt->getTimestamp());
    }

    private function instantiate(): DSLaserOrder
    {
        $this->constructArgs = [
            'id' => $this->id,
            'user' => $this->user,
            'parts' => $this->parts,
            'packages' => $this->packages,
            'visits' => $this->visits,
            'price' => $this->price,
            'neededTime' => $this->neededTime,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt
        ];

        return new DSLaserOrder(...$this->constructArgs);
    }

    public function testToArray(): void
    {
        $dsOrderArray = $this->instantiate()->toArray();

        $this->assertIsArray($dsOrderArray);
        $this->assertCount(count($this->constructArgs), $dsOrderArray);

        foreach ($this->constructArgs as $key => $value) {
            $this->assertNotFalse(array_search($key, array_keys($dsOrderArray)));

            if (gettype($value) !== "object") {
                $this->assertEquals($value, $dsOrderArray[$key]);
            } elseif ($value instanceof \DateTime) {
                $this->assertEquals($value->format("Y-m-d H:i:s"), $dsOrderArray[$key]);
            } else {
                $this->assertEquals($value->toArray(), $dsOrderArray[$key]);
            }
        }
    }
}
