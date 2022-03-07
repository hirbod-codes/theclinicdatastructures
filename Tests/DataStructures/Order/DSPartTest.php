<?php

namespace Tests\DataStructures\Order;

use Faker\Factory;
use Faker\Generator;
use Tests\TestCase;
use TheClinicDataStructures\DataStructures\Order\DSPart;

class DSPartTest extends TestCase
{
    private Generator $faker;

    private int $id;

    private string $gender;

    private string $name;

    private int $price;

    private int $neededTime;

    private \DateTime $createdAt;

    private \DateTime $updatedAt;

    private array $constructArgs;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();

        $this->id = $this->faker->numberBetween(1, 100);

        $this->gender = $this->faker->randomElement(["Male", "Female"]);

        $this->name = $this->faker->name($this->gender);

        $this->price = $this->faker->numberBetween(50000, 600000);

        $this->neededTime = $this->faker->numberBetween(600, 7200);

        $this->createdAt = new \DateTime($this->faker->time("Y-m-d H:i:s"));

        $this->updatedAt = new \DateTime($this->faker->time("Y-m-d H:i:s"));
    }

    public function testToArray(): void
    {

        $dsPartArray = $this->instantiate()->toArray();

        $this->assertIsArray($dsPartArray);
        $this->assertCount(count($this->constructArgs), $dsPartArray);

        foreach ($this->constructArgs as $key => $value) {
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

    private function instantiate(): DSPart
    {
        $this->constructArgs = [
            'id' => $this->id,
            'name' => $this->name,
            'gender' => $this->gender,
            'price' => $this->price,
            'neededTime' => $this->neededTime,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt
        ];
        return new DSPart(...array_values($this->constructArgs));
    }

    public function testDataStructure(): void
    {
        $dsPart = $this->instantiate();

        $this->assertEquals($dsPart->getId(), $this->id);
        $this->assertEquals($dsPart->getName(), $this->name);
        $this->assertEquals($dsPart->getGender(), $this->gender);
        $this->assertEquals($dsPart->getPrice(), $this->price);
        $this->assertEquals($dsPart->getNeededTime(), $this->neededTime);
        $this->assertEquals($dsPart->getCreatedAt()->getTimestamp(), $this->createdAt->getTimestamp());
        $this->assertEquals($dsPart->getUpdatedAt()->getTimestamp(), $this->updatedAt->getTimestamp());
    }
}
