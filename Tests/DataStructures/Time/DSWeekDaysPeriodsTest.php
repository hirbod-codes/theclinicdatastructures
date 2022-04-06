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

    private function makeDSDateTimePeriods(\DateTime &$s, \DateTime &$e, int $count): DSDateTimePeriods
    {
        $dsDateTimePeriods = new DSDateTimePeriods;
        $e->modify('+5 hours');
        for ($i = 0; $i < $count; $i++) {
            $dsDateTimePeriods[] = new DSDateTimePeriod($s, $e);
            $s = (new \DateTime)->setTimestamp($e->getTimestamp())->modify('+5 minutes');
            $e = (new \DateTime)->setTimestamp($s->getTimestamp())->modify('+5 hours');
        }

        return $dsDateTimePeriods;
    }

    private function makeDSWeekDaysPeriods(int $count): DSWeekDaysPeriods
    {
        $dsWeekDaysPeriods = new DSWeekDaysPeriods($day = (new \DateTime())->format('l'));
        for ($i = 0; $i < 7; $i++) {
            $s = (new \DateTime)->setTime(0, 0, 0)->modify('+' . $i . ' days');
            $e = (new \DateTime)->setTime(0, 0, 0)->modify('+' . $i . ' days');
            $dsWeekDaysPeriods[$i] = $this->makeDSDateTimePeriods($s, $e, $count);
        }

        return $dsWeekDaysPeriods;
    }

    public function testToArray(): void
    {
        $dsWeekDaysPeriods = $this->makeDSWeekDaysPeriods($count = 3);

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

    public function testToObject(): void
    {
        $expectedDSWeekDaysPeriods = $this->makeDSWeekDaysPeriods($count = 3);
        $expectedDSWeekDaysPeriods->setStartingDay('Monday');

        $dsWeekDaysPeriodsArray = $expectedDSWeekDaysPeriods->toArray();

        $dsWeekDaysPeriods = DSWeekDaysPeriods::toObject($dsWeekDaysPeriodsArray);

        $this->assertInstanceOf(DSWeekDaysPeriods::class, $dsWeekDaysPeriods);
        $this->assertCount(7, $dsWeekDaysPeriods);

        /**
         *  @var DSDateTimePeriods[] $dsWeekDaysPeriods 
         *  @var DSDateTimePeriods[] $expectedDSWeekDaysPeriods 
         */
        for ($i = 0; $i < count($dsWeekDaysPeriods); $i++) {
            $dateTimePeriods = $dsWeekDaysPeriods[$i];
            $this->assertCount($count, $dateTimePeriods);
            for ($j = 0; $j < count($dateTimePeriods); $j++) {
                $this->assertEquals($expectedDSWeekDaysPeriods[$i][$j]->getStartTimestamp(), $dateTimePeriods[$j]->getStartTimestamp());
                $this->assertEquals($expectedDSWeekDaysPeriods[$i][$j]->getEndTimestamp(), $dateTimePeriods[$j]->getEndTimestamp());
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
        $dsWeekDaysPeriods = new DSWeekDaysPeriods($day = (new \DateTime())->format('l'));
        $counter = 0;
        $s = (new \DateTime)->setTime(0, 0, 0);
        $e = (new \DateTime)->setTime(0, 0, 0);
        $dsWeekDaysPeriods[$day] = $this->makeDSDateTimePeriods($s, $e, $count);
        $this->assertInstanceOf(DSDateTimePeriods::class, $dsWeekDaysPeriods[$day]);

        $dsWeekDaysPeriods = $this->makeDSWeekDaysPeriods($count);

        foreach (DSWeekDaysPeriods::$weekDays as $day) {
            $this->assertInstanceOf(DSDateTimePeriods::class, $dsWeekDaysPeriods[$day]);
            $this->assertEquals(true, isset($dsWeekDaysPeriods[$day]));
            $counter++;
        }

        $this->assertCount(7, $dsWeekDaysPeriods);
    }

    private function testIterator(): void
    {
        $dsWeekDaysPeriods = $this->makeDSWeekDaysPeriods($count = 3);

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
