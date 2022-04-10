<?php

namespace TheClinicDataStructures\Tests\DataStructures\Visit\Laser;

use Faker\Factory;
use Faker\Generator;
use Mockery;
use Tests\TestCase;
use TheClinicDataStructures\DataStructures\Visit\Laser\DSLaserVisit;
use TheClinicDataStructures\DataStructures\Visit\Laser\DSLaserVisits;
use TheClinicDataStructures\Exceptions\DataStructures\Visit\VisitExceptions;

class DSLaserVisitsTest extends TestCase
{
    private Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function testDataStructure(): void
    {
        $visitCount = 7;

        $this->testDSVisits($visitCount, "ASC");
        $this->testDSVisits($visitCount, "DESC");
        $this->testDSVisits($visitCount, "Natural");
    }

    public function testToArray(): void
    {
        $pointer = new \DateTime();
        /** @var array $visits */
        $visits = $this->makeVisits($pointer, 'ASC', 10);

        $dsVisits = new DSLaserVisits('ASC');
        foreach ($visits as $visit) {
            $dsVisits[] = $visit;
        }

        $dsVisitsArray = $dsVisits->toArray();

        $this->assertIsArray($dsVisitsArray);
        $this->assertCount(1, $dsVisitsArray);

        $this->assertNotFalse(array_search('visits', array_keys($dsVisitsArray)));
        $this->assertCount(10, $dsVisitsArray['visits']);

        foreach ($dsVisitsArray['visits'] as $dsVisitArray) {
            $this->assertIsArray($dsVisitArray);
            $this->assertCount(7, $dsVisitArray);
        }
    }

    /**
     * @param integer $visitCount This variable must have the value of at least 5.
     * @param string $sort one of the following "ASC", "DESC", "Natural".
     * @return void
     */
    private function testDSVisits(int $visitCount, string $sort): void
    {
        if (!in_array($sort, ["ASC", "DESC", "Natural"])) {
            throw new \Exception("The value of \$sort is not acceptable", 1);
        }

        $this->testVisitsArrayAccessInterface($visitCount, $sort);
        $this->testVisitsIteratorInterface($visitCount, $sort);
    }

    private function testVisitsArrayAccessInterface(int $visitCount, string $sort): void
    {
        $this->testArrayAccessWithNullOffset($visitCount, $sort);
        $this->testArrayAccessWithIntegerOffset($visitCount, $sort);
    }

    private function testVisitsIteratorInterface(int $visitCount, string $sort): void
    {
        $pointer = new \DateTime();
        /** @var array $visits */
        $visits = $this->makeVisits($pointer, $sort, $visitCount);

        $dsVisits = new DSLaserVisits($sort);
        foreach ($visits as $visit) {
            $dsVisits[] = $visit;
        }

        $this->assertEquals(count($visits), count($dsVisits));

        $dsVisits = new DSLaserVisits($sort);

        $counter = 0;
        foreach ($dsVisits as $key => $visit) {
            if (!($visit instanceof DSLaserVisit)) {
                throw new \RuntimeException("visits must be of type: " . DSLaserVisit::class . ".", 500);
            }

            $dsVisits[] = $visit;

            $counter++;
        }

        $this->assertEquals($counter, count($dsVisits));
    }

    private function testArrayAccessWithNullOffset(int $visitCount, string $sort): void
    {
        $pointer = new \DateTime();
        /** @var array $visits */
        $visits = $this->makeVisits($pointer, $sort, $visitCount);

        $dsVisits = new DSLaserVisits($sort);
        foreach ($visits as $visit) {
            $dsVisits[] = $visit;
        }

        $this->assertEquals(count($visits), count($dsVisits));

        switch ($sort) {
            case 'ASC':
                $this->testASCNullOffset($pointer, $dsVisits);
                break;

            case 'DESC':
                $this->testDESCNullOffset($pointer, $dsVisits);
                break;

            case 'Natural':
                $this->TestNaturalNullOffset($pointer, $dsVisits);
                break;

            default:
                break;
        }
    }

    private function testArrayAccessWithIntegerOffset(int $visitCount, string $sort): void
    {
        $pointer = new \DateTime();
        /** @var array $visits */
        $visits = $this->makeVisits($pointer, $sort, $visitCount);

        $dsVisits = new DSLaserVisits($sort);
        for ($i = 0; $i < $visitCount; $i++) {
            $dsVisits[$i] = $visits[$i];
        }

        $this->assertEquals(count($visits), count($dsVisits));

        switch ($sort) {
            case 'ASC':
                $this->testASCIntegerOffset($pointer, $dsVisits);
                break;

            case 'DESC':
                $this->testDESCIntegerOffset($pointer, $dsVisits);
                break;

            case 'Natural':
                $this->TestNaturalIntegerOffset($pointer, $dsVisits);
                break;

            default:
                break;
        }
    }

    private function testASCIntegerOffset(\DateTime $pointer, DSLaserVisits $dsVisits): void
    {
        /** @var array|DSLaserVisits $dsVisits */
        $dsVisits[$dsVisits->findLastPosition() + 1] = $this->makeCustomVisit((new \DateTime())->setTimestamp($pointer->getTimestamp())->modify("+30 minutes")->getTimestamp(), 1800);

        $dsVisits[0] = $this->makeCustomVisit($dsVisits[0]->getVisitTimestamp() + 10, 1800);

        unset($dsVisits[1]);
        unset($dsVisits[3]);

        $dsVisits[2] = $this->makeCustomVisit((new \DateTime())->setTimestamp($dsVisits[2]->getVisitTimestamp())->modify("-10 minutes")->getTimestamp(), 1800);

        try {
            $dsVisits[3] = $this->makeCustomVisit((new \DateTime())->setTimestamp($dsVisits[4]->getVisitTimestamp())->modify("-5 minutes")->getTimestamp(), 1800);
            throw new \RuntimeException("", 500);
        } catch (VisitExceptions $th) {
        }

        try {
            $dsVisits[3] = $this->makeCustomVisit((new \DateTime())->setTimestamp($dsVisits[4]->getVisitTimestamp())->modify("+5 minutes")->getTimestamp(), 1200);
            throw new \RuntimeException("", 500);
        } catch (VisitExceptions $th) {
        }
    }

    private function testDESCIntegerOffset(\DateTime $pointer, DSLaserVisits $dsVisits): void
    {
        /** @var array|DSLaserVisits $dsVisits */
        $dsVisits[$dsVisits->findLastPosition() + 1] = $this->makeCustomVisit((new \DateTime())->setTimestamp($pointer->getTimestamp())->modify("-30 minutes")->getTimestamp(), 1800);

        $dsVisits[0] = $this->makeCustomVisit($dsVisits[0]->getVisitTimestamp() - 600, 1800);

        unset($dsVisits[1]);
        unset($dsVisits[3]);

        $dsVisits[2] = $this->makeCustomVisit((new \DateTime())->setTimestamp($dsVisits[2]->getVisitTimestamp())->modify("-10 minutes")->getTimestamp(), 1800);

        try {
            $dsVisits[3] = $this->makeCustomVisit((new \DateTime())->setTimestamp($dsVisits[2]->getVisitTimestamp())->modify("-5 minutes")->getTimestamp(), 1800);
            throw new \RuntimeException("", 500);
        } catch (VisitExceptions $th) {
        }

        try {
            $dsVisits[3] = $this->makeCustomVisit((new \DateTime())->setTimestamp($dsVisits[2]->getVisitTimestamp())->modify("+5 minutes")->getTimestamp(), 1200);
            throw new \RuntimeException("", 500);
        } catch (VisitExceptions $th) {
        }
    }

    private function TestNaturalIntegerOffset(\DateTime $pointer, DSLaserVisits $dsVisits): void
    {
        $key = $this->faker->numberBetween(0, count($dsVisits) - 2);

        $dsVisits[$key] = $this->makeCustomVisit((new \DateTime())->setTimestamp($dsVisits[$key]->getVisitTimestamp())->getTimestamp(), 1800);

        try {
            $dsVisits[$key] = $this->makeCustomVisit((new \DateTime())->setTimestamp($dsVisits[$key + 1]->getVisitTimestamp())->modify("-29 minutes")->getTimestamp(), 1800);

            throw new \RuntimeException("", 500);
        } catch (VisitExceptions $th) {
        }
    }

    private function TestASCNullOffset(\DateTime $pointer, DSLaserVisits $dsVisits): void
    {
        try {
            $dsVisits[] = $this->makeCustomVisit((new \DateTime())->setTimestamp($pointer->getTimestamp())->modify("-30 minutes")->getTimestamp(), 1800);

            throw new \RuntimeException("", 500);
        } catch (VisitExceptions $th) {
        }

        try {
            $dsVisits[] = $this->makeCustomVisit((new \DateTime())->setTimestamp($pointer->getTimestamp())->modify("+1 minute")->getTimestamp(), 1800);

            throw new \RuntimeException("", 500);
        } catch (VisitExceptions $th) {
        }

        $dsVisits[] = $this->makeCustomVisit((new \DateTime())->setTimestamp($pointer->getTimestamp())->modify("+30 minute")->getTimestamp(), 1800);
    }

    private function TestDESCNullOffset(\DateTime $pointer, DSLaserVisits $dsVisits): void
    {
        try {
            $dsVisits[] = $this->makeCustomVisit((new \DateTime())->setTimestamp($pointer->getTimestamp())->modify("+30 minutes")->getTimestamp(), 1800);

            throw new \RuntimeException("", 500);
        } catch (VisitExceptions $th) {
        }

        try {
            $dsVisits[] = $this->makeCustomVisit((new \DateTime())->setTimestamp($pointer->getTimestamp())->modify("-29 minute")->getTimestamp(), 1800);

            throw new \RuntimeException("", 500);
        } catch (VisitExceptions $th) {
        }

        $dsVisits[] = $this->makeCustomVisit((new \DateTime())->setTimestamp($pointer->getTimestamp())->modify("-30 minute")->getTimestamp(), 1800);
    }

    private function TestNaturalNullOffset(\DateTime $pointer, DSLaserVisits $dsVisits): void
    {
        try {
            $dsVisits[] = $this->makeCustomVisit((new \DateTime())->setTimestamp($pointer->getTimestamp())->modify("+1 minute")->getTimestamp(), 1800);

            throw new \RuntimeException("", 500);
        } catch (VisitExceptions $th) {
        }

        try {
            $dsVisits[] = $this->makeCustomVisit((new \DateTime())->setTimestamp($pointer->getTimestamp())->modify("-29 minute")->getTimestamp(), 1800);

            throw new \RuntimeException("", 500);
        } catch (VisitExceptions $th) {
        }

        $dsVisits[] = $this->makeCustomVisit((new \DateTime())->setTimestamp($pointer->getTimestamp())->modify("-30 minute")->getTimestamp(), 1800);
        $dsVisits[] = $this->makeCustomVisit((new \DateTime())->setTimestamp($pointer->getTimestamp())->modify("+30 minute")->getTimestamp(), 1800);
    }

    private function makeVisits(\DateTime &$pointer, string $sort, int $visitCount): array
    {
        $visits = [];

        for ($i = 0; $i < $visitCount; $i++) {
            switch ($sort) {
                case 'ASC':
                    $visits[] = $this->makeAscendingVisit($pointer);
                    break;

                case 'DESC':
                    $visits[] = $this->makeDescendingVisit($pointer);
                    break;

                case 'Natural':
                    $visits[] = $this->makeRandomVisit($pointer, $i);
                    break;

                default:
                    break;
            }
        }

        return $visits;
    }

    private function makeCustomVisit(int $visitTimestamp, int $consumingTime): DSLaserVisit
    {
        return new DSLaserVisit(
            $this->faker->numberBetween(1, 1000),
            $visitTimestamp,
            $consumingTime,
            new \DateTime,
            new \DateTime,
        );
    }

    private function makeRandomVisit(\DateTime &$pointer, int $callsCount): DSLaserVisit
    {
        if (($callsCount + 1) % 2 === 0) {
            $visitTimestamp = $pointer->modify("+" . (2 * ($callsCount + 1)) . " hours")->getTimestamp();
        } else {
            $visitTimestamp = $pointer->modify("-" . (2 * ($callsCount + 1)) . " hours")->getTimestamp();
        }
        return new DSLaserVisit(
            $this->faker->numberBetween(1, 1000),
            $visitTimestamp,
            1800,
            new \DateTime,
            new \DateTime,
        );
    }

    private function makeAscendingVisit(\DateTime &$pointer): DSLaserVisit
    {
        return new DSLaserVisit(
            $this->faker->numberBetween(1, 1000),
            $pointer->modify("+2 hours")->getTimestamp(),
            1800,
            new \DateTime,
            new \DateTime,
        );
    }

    private function makeDescendingVisit(\DateTime &$pointer): DSLaserVisit
    {
        return new DSLaserVisit(
            $this->faker->numberBetween(1, 1000),
            $pointer->modify("-2 hours")->getTimestamp(),
            1800,
            new \DateTime,
            new \DateTime,
        );
    }
}
