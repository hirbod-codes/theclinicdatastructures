<?php

namespace Tests\DataStructures\Time;

use Faker\Factory;
use Faker\Generator;
use Tests\TestCase;
use TheClinicDataStructures\DataStructures\Time\DSDateTimePeriod;
use TheClinicDataStructures\Exceptions\DataStructures\Time\TimeSequenceViolationException;

class DSDateTimePeriodTest extends TestCase
{
    private Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function testToArray(): void
    {
        $start = new \DateTime();
        $end = (new \DateTime())->modify("+10 minute");

        $dsDateTimePeriod = new DSDateTimePeriod($start, $end);

        $dsDateTimePeriodArray = $dsDateTimePeriod->toArray();

        $this->assertIsArray($dsDateTimePeriodArray);
        $this->assertCount(2, $dsDateTimePeriodArray);

        $this->assertNotFalse(array_search('start', array_keys($dsDateTimePeriodArray)));
        $this->assertEquals($start->format("Y-m-d H:i:s"), $dsDateTimePeriodArray['start']);

        $this->assertNotFalse(array_search('end', array_keys($dsDateTimePeriodArray)));
        $this->assertEquals($end->format("Y-m-d H:i:s"), $dsDateTimePeriodArray['end']);
    }

    public function testToObject(): void
    {
        $start = new \DateTime();
        $end = (new \DateTime())->modify("+10 minute");

        $dsDateTimePeriod = DSDateTimePeriod::toObject(($expectedDSDateTimePeriod = new DSDateTimePeriod($start, $end))->ToArray());

        $this->assertInstanceOf(DSDateTimePeriod::class, $dsDateTimePeriod);
        $this->assertEquals($expectedDSDateTimePeriod->getStartTimestamp(), $dsDateTimePeriod->getStartTimestamp());
        $this->assertEquals($expectedDSDateTimePeriod->getEndTimestamp(), $dsDateTimePeriod->getEndTimestamp());
    }

    public function testDataStructure(): void
    {
        $start = new \DateTime();
        $end = (new \DateTime())->modify("+10 minute");

        $dsDateTimePeriod = new DSDateTimePeriod($start, $end);

        $this->assertEquals($start->getTimestamp(), $dsDateTimePeriod->getStartTimestamp());
        $this->assertEquals($end->getTimestamp(), $dsDateTimePeriod->getEndTimestamp());

        try {
            $dsDateTimePeriod->setEnd((new \DateTime())->setTimestamp($end->getTimestamp())->modify("-11 minute"));

            throw new \LogicException("We can't have the end date time sooner than the start date time.", 500);
        } catch (TimeSequenceViolationException $th) {
        }

        $this->assertEquals($end->getTimestamp(), $dsDateTimePeriod->getEndTimestamp());

        try {
            $dsDateTimePeriod->setStart((new \DateTime())->setTimestamp($start->getTimestamp())->modify("+11 minute"));

            throw new \LogicException("We can't have the start date time further than the end date time.", 500);
        } catch (TimeSequenceViolationException $th) {
        }

        try {
            new DSDateTimePeriod(new \DateTime(), (new \DateTime())->modify("-1 minute"));

            throw new \LogicException("We can't have the end date time sooner than the start date time.", 500);
        } catch (TimeSequenceViolationException $th) {
        }

        $this->assertEquals($start->getTimestamp(), $dsDateTimePeriod->getStartTimestamp());
    }

    public function testSetDate(): void
    {
        $start = new \DateTime('2022-4-6 10:00:00');
        $end = (new \DateTime('2022-4-7 22:00:00'));

        $dsDateTimePeriod = new DSDateTimePeriod($start, $end);

        $this->assertEquals($start->getTimestamp(), $dsDateTimePeriod->getStartTimestamp());
        $this->assertEquals($end->getTimestamp(), $dsDateTimePeriod->getEndTimestamp());

        $dsDateTimePeriod->setDate(new \DateTime('2022-4-8'));

        $this->assertEquals($start->modify('+2 days')->getTimestamp(), $dsDateTimePeriod->getStartTimestamp());
        $this->assertEquals($end->modify('+1 days')->getTimestamp(), $dsDateTimePeriod->getEndTimestamp());

        $start = new \DateTime('2022-4-6 22:00:00');
        $end = (new \DateTime('2022-4-7 10:00:00'));

        $dsDateTimePeriod = new DSDateTimePeriod($start, $end);

        $this->assertEquals($start->getTimestamp(), $dsDateTimePeriod->getStartTimestamp());
        $this->assertEquals($end->getTimestamp(), $dsDateTimePeriod->getEndTimestamp());

        try {
            $dsDateTimePeriod->setDate(new \DateTime('2022-4-8'));
            throw new \RuntimeException('Failure!!!');
        } catch (TimeSequenceViolationException $th) {
        }
    }

    public function testSetTime():void
    {
        $start = new \DateTime('2022-4-6 10:00:00');
        $end = (new \DateTime('2022-4-7 22:00:00'));

        $dsDateTimePeriod = new DSDateTimePeriod($start, $end);

        $this->assertEquals($start->getTimestamp(), $dsDateTimePeriod->getStartTimestamp());
        $this->assertEquals($end->getTimestamp(), $dsDateTimePeriod->getEndTimestamp());

        $dsDateTimePeriod->setTime(new \DateTime('15:00:00'));

        $this->assertEquals($start->modify('+5 hours')->getTimestamp(), $dsDateTimePeriod->getStartTimestamp());
        $this->assertEquals($end->modify('-7 hours')->getTimestamp(), $dsDateTimePeriod->getEndTimestamp());

        $start = new \DateTime('2022-4-7 10:00:00');
        $end = (new \DateTime('2022-4-7 22:00:00'));

        $dsDateTimePeriod = new DSDateTimePeriod($start, $end);

        $this->assertEquals($start->getTimestamp(), $dsDateTimePeriod->getStartTimestamp());
        $this->assertEquals($end->getTimestamp(), $dsDateTimePeriod->getEndTimestamp());

        try {
            $dsDateTimePeriod->setTime(new \DateTime('15:00:00'));
            throw new \RuntimeException('Failure!!!');
        } catch (TimeSequenceViolationException $th) {
        }
    }
}
