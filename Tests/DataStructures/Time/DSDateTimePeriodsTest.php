<?php

namespace Tests\DataStructures\Time;

use Faker\Factory;
use Faker\Generator;
use Tests\TestCase;
use TheClinicDataStructures\DataStructures\Time\DSDateTimePeriod;
use TheClinicDataStructures\DataStructures\Time\DSDateTimePeriods;

class DSDateTimePeriodsTest extends TestCase
{
    private Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    private function makeTimePeriod(\DateTime $previous, string $firstModify, string $secondModify): array
    {
        $s = (new \DateTime)->setTimestamp($previous->getTimestamp())->modify($firstModify);
        $e = (new \DateTime)->setTimestamp($s->getTimestamp())->modify($secondModify);
        return [$s, $e];
    }

    private function makeDSDateTimePeriods(int $count): DSDateTimePeriods
    {
        $dsDateTimePeriods = new DSDateTimePeriods;
        $previous = new \DateTime;
        for ($i = 0; $i < $count - 1; $i++) {
            $dsDateTimePeriods[$i] = new DSDateTimePeriod(...($period = $this->makeTimePeriod($previous, '+5 minutes', '+5 hours')));
            $previous = $period[1];
        }
        $dsDateTimePeriods[] = new DSDateTimePeriod(...($period = $this->makeTimePeriod($previous, '+5 minutes', '+5 hours')));
        $previous = $period[1];

        return $dsDateTimePeriods;
    }

    public function testToArray(): void
    {
        $count = 3;
        $dsDateTimePeriods = $this->makeDSDateTimePeriods($count);

        $dsDateTimePeriodsArray = $dsDateTimePeriods->toArray();

        $this->assertIsArray($dsDateTimePeriodsArray);
        $this->assertCount($count, $dsDateTimePeriodsArray);

        foreach ($dsDateTimePeriodsArray as $key => $value) {
            $this->assertIsArray($value);
            $this->assertCount(2, $value);
        }
    }

    public function testDataStructure(): void
    {
        $this->testArrayAccess();
        $this->testIterator();
    }

    private function testArrayAccess(): void
    {
        $count = 3;
        $dsDateTimePeriods = $this->makeDSDateTimePeriods($count);

        $this->assertCount($count, $dsDateTimePeriods);
        $this->assertInstanceOf(DSDateTimePeriod::class, $dsDateTimePeriods[0]);
    }

    private function testIterator(): void
    {
        $count = 3;
        $dsDateTimePeriods = $this->makeDSDateTimePeriods($count);

        $counter = 0;
        foreach ($dsDateTimePeriods as $key => $value) {
            $this->assertInstanceOf(DSDateTimePeriod::class, $value);

            $counter++;
        }

        $this->assertEquals($count, $counter);
        $this->assertCount($counter, $dsDateTimePeriods);
    }
}
