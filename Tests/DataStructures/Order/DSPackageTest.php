<?php

namespace Tests\DataStructures\Order;

use Faker\Factory;
use Faker\Generator;
use Mockery;
use Tests\TestCase;
use TheClinicDataStructures\DataStructures\Order\DSPackage;
use TheClinicDataStructures\DataStructures\Order\DSParts;
use TheClinicDataStructures\Exceptions\DataStructures\Order\OrderExceptions;

class DSPackageTest extends TestCase
{
    private Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();

        $this->id = $this->faker->numberBetween(1, 100);
        $this->name = $this->faker->lexify();
        $this->gender = "Male";
        $this->price = $this->faker->numberBetween(50000, 600000);

        /** @var \TheClinicDataStructures\DataStructures\Order\DSParts|\Mockery\MockInterface $parts */
        $this->parts = Mockery::mock(DSParts::class);
        $this->parts->shouldReceive("getGender")->andReturn($this->gender);
        $this->parts->shouldReceive("toArray")->andReturn(['parts']);


        $this->createdAt = new \DateTime($this->faker->time("Y-m-d H:i:s"));
        $this->updatedAt = new \DateTime($this->faker->time("Y-m-d H:i:s"));
    }

    public function testToArray(): void
    {
        $dsPartArray = $this->instantiate()->toArray();

        $this->assertIsArray($dsPartArray);
        $this->assertCount(count($this->constructArgs), $dsPartArray);

        foreach ($dsPartArray as $key => $value) {
            $this->assertNotFalse(array_search($key, array_keys($dsPartArray)));

            if (gettype($value) !== "object") {
                $this->assertEquals($value, $dsPartArray[$key]);
            } elseif ($value instanceof \DateTime) {
                $this->assertEquals($value->format("Y-m-d H:i:s"), $dsPartArray[$key]);
            } else {
                // mock the toArray method of other object properties.
                $this->assertEquals($value->toArray(), $dsPartArray[$key]);
            }
        }
    }

    public function testDataStructure(): void
    {
        $this->runTheAssertions();

        /** @var \TheClinicDataStructures\DataStructures\Order\DSParts|\Mockery\MockInterface $parts */
        $this->parts = Mockery::mock(DSParts::class);
        $this->parts->shouldReceive("getGender")->andReturn("Female");

        try {
            $this->runTheAssertions();
        } catch (OrderExceptions $th) {
            /** @var \TheClinicDataStructures\DataStructures\Order\DSParts|\Mockery\MockInterface $parts */
            $this->parts = Mockery::mock(DSParts::class);
            $this->parts->shouldReceive("getGender")->andReturn($this->gender);
        }
    }

    private function runTheAssertions(): void
    {
        $dsPart = $this->instantiate();

        $this->assertEquals($dsPart->getId(), $this->id);
        $this->assertEquals($dsPart->getName(), $this->name);
        $this->assertEquals($dsPart->getGender(), $this->gender);
        $this->assertEquals($dsPart->getPrice(), $this->price);
        $this->assertEquals($dsPart->getParts(), $this->parts);
        $this->assertEquals($dsPart->getCreatedAt()->getTimestamp(), $this->createdAt->getTimestamp());
        $this->assertEquals($dsPart->getUpdatedAt()->getTimestamp(), $this->updatedAt->getTimestamp());
    }

    private function instantiate(): DSPackage
    {
        $this->constructArgs = [
            'id' => $this->id,
            'name' => $this->name,
            'gender' => $this->gender,
            'price' => $this->price,
            'parts' => $this->parts,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt
        ];

        return new DSPackage(...array_values($this->constructArgs));
    }
}
