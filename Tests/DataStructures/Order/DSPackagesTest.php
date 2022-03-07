<?php

namespace Tests\DataStructures\Order;

use Faker\Factory;
use Faker\Generator;
use Mockery;
use Tests\TestCase;
use TheClinicDataStructures\DataStructures\Order\DSPackage;
use TheClinicDataStructures\DataStructures\Order\DSPackages;
use TheClinicDataStructures\Exceptions\DataStructures\Order\InvalidGenderException;

class DSPackagesTest extends TestCase
{
    private Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function testToArray(): void
    {
        $gender = "Male";

        $packages = $this->makePackages($gender, 10);

        $dsPackages = new DSPackages($gender);
        foreach ($packages as $package) {
            $dsPackages[] = $package;
        }
        $dsPackagesArray = $dsPackages->toArray();

        $this->assertIsArray($dsPackagesArray);
        $this->assertCount(2, $dsPackagesArray);

        $this->assertNotFalse(array_search('gender', array_keys($dsPackagesArray)));
        $this->assertEquals($gender, $dsPackagesArray['gender']);

        $this->assertNotFalse(array_search('packages', array_keys($dsPackagesArray)));
        $this->assertCount(10, $dsPackagesArray['packages']);

        foreach ($dsPackagesArray['packages'] as $package) {
            $this->assertIsArray($package);
            $this->assertCount(1, $package);
            $this->assertEquals('package', $package[0]);
        }
    }

    public function testDataStructure(): void
    {
        $gender = "Male";

        $packages = $this->makePackages($gender, 10);

        $this->runTheAssertions($gender, $packages);

        /** @var \TheClinicDataStructures\DataStructures\Order\DSPackage|\Mockery\MockInterface $package */
        $package = Mockery::mock(DSPackage::class);
        $package->shouldReceive("getId")->andReturn($this->faker->numberBetween(1, 1000));
        $package->shouldReceive("getGender")->andReturn("Female");
        $packages[3] = $package;

        try {
            $this->runTheAssertions($gender, $packages);
        } catch (InvalidGenderException $th) {
        }
    }

    private function makePackages(string $gender, int $count): array
    {
        $packages = [];

        for ($i = 0; $i < $count; $i++) {
            /** @var \TheClinicDataStructures\DataStructures\Order\DSPackage|\Mockery\MockInterface $package */
            $package = Mockery::mock(DSPackage::class);
            $package->shouldReceive("getId")->andReturn($this->faker->numberBetween(1, 1000));
            $package->shouldReceive("getGender")->andReturn($gender);
            $package->shouldReceive("toArray")->andReturn(['package']);

            $packages[] = $package;
        }

        return $packages;
    }

    private function runTheAssertions(string $gender, array $packages): void
    {
        $dsPackages = new DSPackages($gender);
        $this->assertEquals($dsPackages->getGender(), $gender);

        foreach ($packages as $package) {
            $dsPackages[] = $package;
        }
        $this->assertCount(count($packages), $dsPackages);

        for ($i = 0; $i < count($packages); $i++) {
            $dsPackages[$i] = $packages[$i];
        }
        $this->assertCount(count($packages), $dsPackages);

        unset($dsPackages[4]);

        $counter = 0;
        /** @var \TheClinicDataStructures\DataStructures\Order\DSPackage $package */
        foreach ($dsPackages as $package) {
            $this->assertInstanceOf(DSPackage::class, $package);

            $this->assertEquals($package->getGender(), $dsPackages->getGender());

            $counter++;
        }

        $this->assertEquals(count($packages) - 1, $counter);
    }
}
