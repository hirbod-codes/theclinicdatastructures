<?php

namespace Tests\DataStructures\Time;

use Faker\Factory;
use Faker\Generator;
use Mockery;
use Tests\TestCase;
use TheClinicDataStructures\DataStructures\Time\DSTimePeriods;
use TheClinicDataStructures\DataStructures\Time\DSWeekDaysPeriods;

class DSWeekDaysPeriodsTest extends TestCase
{
    private Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function testToArray(): void
    {
        $dsWeekDaysPeriods = new DSWeekDaysPeriods("Monday");
        for ($i = 0; $i < 7; $i++) {
            /** @var \Mockery\MockInterface $dsWeekDaysPeriod */
            $dsWeekDaysPeriod = Mockery::mock(DSTimePeriods::class);
            $dsWeekDaysPeriod->shouldReceive('toArray')->andReturn(['dsWeekDaysPeriod']);

            $dsWeekDaysPeriods[$i] = $dsWeekDaysPeriod;
        }

        $dsWeekDaysPeriodsArray = $dsWeekDaysPeriods->toArray();

        $this->assertIsArray($dsWeekDaysPeriodsArray);
        $this->assertCount(7, $dsWeekDaysPeriodsArray);

        foreach ($dsWeekDaysPeriodsArray as $key => $value) {
            $this->assertNotFalse(array_search($key, array_keys($dsWeekDaysPeriodsArray)));

            $this->assertIsArray($value);
            $this->assertCount(1, $value);
            $this->assertEquals('dsWeekDaysPeriod', $value[0]);
        }
    }

    public function testDataStructure(): void
    {
        $this->testArrayAccess();
        $this->testIterator();
    }

    private function testArrayAccess(): void
    {
        $dsWeekDaysPeriods = new DSWeekDaysPeriods("Monday");
        for ($i = 0; $i < 7; $i++) {
            $dsWeekDaysPeriods[$i] = Mockery::mock(DSTimePeriods::class);
            $this->assertEquals(true, isset($dsWeekDaysPeriods[$i]));
        }

        $dsWeekDaysPeriods = new DSWeekDaysPeriods("Monday");
        foreach (DSWeekDaysPeriods::$weekDays as $day) {
            $dsWeekDaysPeriods[$day] = Mockery::mock(DSTimePeriods::class);
            $this->assertEquals(true, isset($dsWeekDaysPeriods[$day]));
        }

        $this->assertCount(7, $dsWeekDaysPeriods);
    }

    private function testIterator(): void
    {
        $dsWeekDaysPeriods = new DSWeekDaysPeriods("Monday");
        for ($i = 0; $i < 7; $i++) {
            $dsWeekDaysPeriods[$i] = Mockery::mock(DSTimePeriods::class);
            $this->assertEquals(true, isset($dsWeekDaysPeriods[$i]));
        }

        $counter = 0;
        foreach ($dsWeekDaysPeriods as $key => $value) {
            if (!in_array($key, DSWeekDaysPeriods::$weekDays, true)) {
                throw new \RuntimeException("Invalid key!!!", 500);
            }

            $this->assertInstanceOf(DSTimePeriods::class, $value);
            $counter++;
        }

        $this->assertEquals($counter, count($dsWeekDaysPeriods));
    }
}
