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
}
