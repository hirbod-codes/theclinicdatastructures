<?php

namespace Tests\DataStructures\User;

use Faker\Factory;
use Faker\Generator;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;
use TheClinicDataStructures\DataStructures\Order\DSOrders;
use TheClinicDataStructures\DataStructures\User\DSOperator;
use TheClinicDataStructures\DataStructures\User\DSUser;
use TheClinicDataStructures\DataStructures\User\ICheckAuthentication;
use TheClinicDataStructures\DataStructures\Visit\DSVisits;

class DSOperatorTest extends TestCase
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

    private function instanciate(): DSOperator
    {
        return new DSOperator(
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
        $dsOperator = $this->instanciate();

        $this->iCheckAuthentication->shouldReceive("isAuthenticated")->with($dsOperator)->andReturn(true);

        $this->assertEquals($this->id, $dsOperator->getId());
        $this->assertEquals($this->firstname, $dsOperator->getFirstname());
        $this->assertEquals($this->lastname, $dsOperator->getLastname());
        $this->assertEquals($this->username, $dsOperator->getUsername());
        $this->assertEquals($this->password, $dsOperator->getPassword());
        $this->assertEquals($this->gender, $dsOperator->getGender());
        $this->assertEquals($this->visits, $dsOperator->visits);
        $this->assertEquals($this->orders, $dsOperator->orders);
        $this->assertEquals($this->createdAt, $dsOperator->getCreatedAt());
        $this->assertEquals($this->updatedAt, $dsOperator->getUpdatedAt());

        $this->assertEquals(true, $dsOperator->isAuthenticated());
    }

    public function testGetRuleName(): void
    {
        $this->assertEquals("operator", $this->instanciate()->getRuleName());
    }

    public function testGetUserPrivileges(): void
    {
        $orignalPrivileges = json_decode(file_get_contents(DSUser::PRIVILEGES_PATH . "/operatorPrivileges.json"), true);
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
        $dsOperator = $this->instanciate();

        $privilege = "selfAccountRead";

        $result = $dsOperator->privilegeExists($privilege);
        $this->assertTrue($result);

        $privilege = "dummyPrivilege";

        $result = $dsOperator->privilegeExists($privilege);
        $this->assertFalse($result);
    }

    public function testGetPrivilege(): void
    {
        $privilege = "selfAccountRead";

        $dsOperator = $this->instanciate();

        $result = $dsOperator->getPrivilege($privilege);
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testSetPrivilege(): void
    {
        try {
            $privilege = "selfAccountRead";

            $dsOperator = $this->instanciate();

            $dsOperator->setPrivilege($privilege, false);

            $result = $dsOperator->getPrivilege($privilege);
            $this->assertIsBool($result);
            $this->assertFalse($result);
        } finally {
            $dsOperator->setPrivilege($privilege, true);
        }
    }
}
