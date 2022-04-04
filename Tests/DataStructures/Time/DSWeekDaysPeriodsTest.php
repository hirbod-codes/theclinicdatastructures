<?php

namespace Tests\DataStructures\Time;

use Faker\Factory;
use Faker\Generator;
use Tests\TestCase;
use TheClinicDataStructures\DataStructures\Time\DSDateTimePeriod;
use TheClinicDataStructures\DataStructures\Time\DSDateTimePeriods;
use TheClinicDataStructures\DataStructures\Time\DSWeekDaysPeriods;

class DSWeekDaysPeriodsTest extends TestCase
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
        for ($i = 0; $i < $count; $i++) {
            $dsDateTimePeriods[] = new DSDateTimePeriod(...($period = $this->makeTimePeriod($previous, '+5 minutes', '+5 hours')));
            $previous = $period[1];
        }

        return $dsDateTimePeriods;
    }

    public function testToArray(): void
    {
        $count = 3;
        $dsWeekDaysPeriods = new DSWeekDaysPeriods("Monday");
        for ($i = 0; $i < 7; $i++) {
            $dsWeekDaysPeriods[$i] = $this->makeDSDateTimePeriods($count);
        }

        $dsWeekDaysPeriodsArray = $dsWeekDaysPeriods->toArray();

        $this->assertIsArray($dsWeekDaysPeriodsArray);
        $this->assertCount(7, $dsWeekDaysPeriodsArray);

        foreach ($dsWeekDaysPeriodsArray as $key => $value) {
            $this->assertNotFalse(array_search($key, array_keys($dsWeekDaysPeriodsArray)));

            $this->assertIsArray($value);
            $this->assertCount($count, $value);
            foreach ($value as $valuevalue) {
                $this->assertIsArray($valuevalue);
                $this->assertCount(2, $valuevalue);
            }
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
        $dsWeekDaysPeriods = new DSWeekDaysPeriods("Monday");
        for ($i = 0; $i < 7; $i++) {
            $dsWeekDaysPeriods[$i] = $this->makeDSDateTimePeriods($count);
            $this->assertEquals(true, isset($dsWeekDaysPeriods[$i]));
        }

        $dsWeekDaysPeriods = new DSWeekDaysPeriods("Monday");
        foreach (DSWeekDaysPeriods::$weekDays as $day) {
            $dsWeekDaysPeriods[$day] = $this->makeDSDateTimePeriods($count);
            $this->assertEquals(true, isset($dsWeekDaysPeriods[$day]));
        }

        $this->assertCount(7, $dsWeekDaysPeriods);
    }

    private function testIterator(): void
    {
        $count = 3;
        $dsWeekDaysPeriods = new DSWeekDaysPeriods("Monday");
        for ($i = 0; $i < 7; $i++) {
            $dsWeekDaysPeriods[$i] = $this->makeDSDateTimePeriods($count);
            $this->assertEquals(true, isset($dsWeekDaysPeriods[$i]));
        }

        $counter = 0;
        foreach ($dsWeekDaysPeriods as $key => $value) {
            if (!in_array($key, DSWeekDaysPeriods::$weekDays, true)) {
                throw new \RuntimeException("Invalid key!!!", 500);
            }

            $this->assertInstanceOf(DSDateTimePeriods::class, $value);
            $counter++;
        }

        $this->assertEquals(7, $counter);
        $this->assertCount($counter, $dsWeekDaysPeriods);
    }
}
