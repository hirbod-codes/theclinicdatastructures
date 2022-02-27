<?php

namespace Tests\DataStructures\User;

use Faker\Factory;
use Faker\Generator;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;
use TheClinicDataStructures\DataStructures\Order\DSOrders;
use TheClinicDataStructures\DataStructures\User\DSPatient;
use TheClinicDataStructures\DataStructures\User\DSUser;
use TheClinicDataStructures\DataStructures\User\ICheckAuthentication;
use TheClinicDataStructures\DataStructures\Visit\DSVisits;

class DSPatientTest extends TestCase
{
    private Generator $faker;

    private $iCheckAuthentication;

    private $id;

    private string $firstname;

    private string $lastname;

    private string $username;

    private string $password;

    private string $gender;

    private null|DSVisits $visits;

    private null|DSOrders $orders;

    private \DateTime $createdAt;

    private \DateTime $updatedAt;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();

        /** @var ICheckAuthentication|MockInterface $iCheckAuthentication */
        $this->iCheckAuthentication = Mockery::mock(ICheckAuthentication::class);

        $this->id = $this->faker->numberBetween(1, 1000);
        $this->firstname = $this->faker->firstName();
        $this->lastname = $this->faker->lastName();
        $this->username = $this->faker->userName();
        $this->password = $this->faker->password(8);
        $this->gender = $this->faker->randomElement(["Male", "Female"]);
        $this->visits = null;
        $this->orders = null;
        $this->createdAt = new \DateTime;
        $this->updatedAt = new \DateTime;
    }

    private function instanciate(): DSPatient
    {
        return new DSPatient(
            $this->iCheckAuthentication,
            $this->id,
            $this->firstname,
            $this->lastname,
            $this->username,
            $this->password,
            $this->gender,
            $this->visits,
            $this->orders,
            $this->createdAt,
            $this->updatedAt
        );
    }

    public function test()
    {
        $dsPatient = $this->instanciate();

        $this->iCheckAuthentication->shouldReceive("isAuthenticated")->with($dsPatient)->andReturn(true);

        $this->assertEquals($this->id, $dsPatient->getId());
        $this->assertEquals($this->firstname, $dsPatient->getFirstname());
        $this->assertEquals($this->lastname, $dsPatient->getLastname());
        $this->assertEquals($this->username, $dsPatient->getUsername());
        $this->assertEquals($this->password, $dsPatient->getPassword());
        $this->assertEquals($this->gender, $dsPatient->getGender());
        $this->assertEquals($this->visits, $dsPatient->visits);
        $this->assertEquals($this->orders, $dsPatient->orders);
        $this->assertEquals($this->createdAt, $dsPatient->getCreatedAt());
        $this->assertEquals($this->updatedAt, $dsPatient->getUpdatedAt());

        $this->assertEquals(true, $dsPatient->isAuthenticated());
    }

    public function testGetRuleName(): void
    {
        $this->assertEquals("patient", $this->instanciate()->getRuleName());
    }

    public function testGetUserPrivileges(): void
    {
        $orignalPrivileges = json_decode(file_get_contents(DSUser::PRIVILEGES_PATH . "/patientPrivileges.json"), true);
        $privilegesCount = count($orignalPrivileges);

        $privileges = $this->instanciate()->getUserPrivileges();

        $this->assertIsArray($privileges);
        $this->assertCount($privilegesCount, $privileges);

        foreach ($privileges as $privilege) {
            foreach ($orignalPrivileges as $orignalPrivilege) {
                if ($privilege === $orignalPrivilege) {
                    continue 2;
                }
            }

            throw new \RuntimeException("Failure!!!", 500);
        }
    }

    public function testPrivilegeExists(): void
    {
        $dsPatient = $this->instanciate();

        $privilege = "selfAccountRead";

        $result = $dsPatient->privilegeExists($privilege);
        $this->assertTrue($result);

        $privilege = "dummyPrivilege";

        $result = $dsPatient->privilegeExists($privilege);
        $this->assertFalse($result);
    }

    public function testGetPrivilege(): void
    {
        $privilege = "selfAccountRead";

        $dsPatient = $this->instanciate();

        $result = $dsPatient->getPrivilege($privilege);
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testSetPrivilege(): void
    {
        try {
            $privilege = "selfAccountRead";

            $dsPatient = $this->instanciate();

            $dsPatient->setPrivilege($privilege, false);

            $result = $dsPatient->getPrivilege($privilege);
            $this->assertIsBool($result);
            $this->assertFalse($result);
        } finally {
            $dsPatient->setPrivilege($privilege, true);
        }
    }
}