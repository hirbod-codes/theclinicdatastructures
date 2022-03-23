<?php

namespace Tests\DataStructures\User;

use Faker\Factory;
use Faker\Generator;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;
use TheClinicDataStructures\DataStructures\Order\DSOrders;
use TheClinicDataStructures\DataStructures\User\DSDoctor;
use TheClinicDataStructures\DataStructures\User\DSUser;
use TheClinicDataStructures\DataStructures\User\ICheckAuthentication;
use TheClinicDataStructures\DataStructures\Visit\DSVisits;

class DSDoctorTest extends TestCase
{
    private Generator $faker;

    private $iCheckAuthentication;

    private $id;

    private string $firstname;

    private string $lastname;

    private string $username;

    private string $gender;

    private null|DSVisits $visits;

    private null|DSOrders $orders;

    private \DateTime $createdAt;

    private \DateTime $updatedAt;

    private array $constructArgs;

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
        $this->gender = $this->faker->randomElement(["Male", "Female"]);
        $this->email = $this->faker->safeEmail();
        $this->emailVerifiedAt = new \DateTime;
        $this->phonenumber = $this->faker->phoneNumber();
        $this->phonenumberVerifiedAt = new \DateTime;
        $this->visits = null;
        $this->orders = null;
        $this->createdAt = new \DateTime;
        $this->updatedAt = new \DateTime;
    }

    private function instanciate(): DSDoctor
    {
        $this->constructArgs = [
            'iCheckAuthentication' => $this->iCheckAuthentication,
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'username' => $this->username,
            'gender' => $this->gender,
            'email' => $this->email,
            'phonenumber' => $this->phonenumber,
            'emailVerifiedAt' => $this->emailVerifiedAt,
            'phonenumberVerifiedAt' => $this->phonenumberVerifiedAt,
            'visits' => $this->visits,
            'orders' => $this->orders,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt
        ];
        return new DSDoctor(...$this->constructArgs);
    }

    public function test()
    {
        $dsDoctor = $this->instanciate();

        $this->iCheckAuthentication->shouldReceive("isAuthenticated")->with($dsDoctor)->andReturn(true);

        $this->assertEquals($this->id, $dsDoctor->getId());
        $this->assertEquals($this->firstname, $dsDoctor->getFirstname());
        $this->assertEquals($this->lastname, $dsDoctor->getLastname());
        $this->assertEquals($this->username, $dsDoctor->getUsername());
        $this->assertEquals($this->gender, $dsDoctor->getGender());
        $this->assertEquals($this->email, $dsDoctor->getEmail());
        $this->assertEquals($this->emailVerifiedAt, $dsDoctor->getEmailVerifiedAt());
        $this->assertEquals($this->phonenumber, $dsDoctor->getPhonenumber());
        $this->assertEquals($this->phonenumberVerifiedAt, $dsDoctor->getPhonenumberVerifiedAt());
        $this->assertEquals($this->visits, $dsDoctor->visits);
        $this->assertEquals($this->orders, $dsDoctor->orders);
        $this->assertEquals($this->createdAt, $dsDoctor->getCreatedAt());
        $this->assertEquals($this->updatedAt, $dsDoctor->getUpdatedAt());

        $this->assertEquals(true, $dsDoctor->isAuthenticated());
    }

    public function testToArray()
    {
        $dsAdminArray = $this->instanciate()->toArray();
        unset($this->constructArgs['iCheckAuthentication']);

        foreach ($this->constructArgs as $key => $value) {
            $this->assertNotFalse(array_search($key, array_keys($dsAdminArray)));
            if (gettype($value) !== "object") {
                $this->assertEquals($value, $dsAdminArray[$key]);
            } elseif ($value instanceof \DateTime) {
                $this->assertEquals($value->format("Y-m-d H:i:s"), $dsAdminArray[$key]);
            } else {
                // mock the toArray method of other object properties.
                $this->assertEquals($value->toArray(), $dsAdminArray[$key]);
            }
        }
    }

    public function testGetRuleName(): void
    {
        $this->assertEquals("doctor", $this->instanciate()->getRuleName());
    }

    public function testGetUserPrivileges(): void
    {
        $orignalPrivileges = json_decode(file_get_contents(DSUser::PRIVILEGES_PATH . "/doctorPrivileges.json"), true);
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
        $dsDoctor = $this->instanciate();

        $privilege = "selfAccountRead";

        $result = $dsDoctor->privilegeExists($privilege);
        $this->assertTrue($result);

        $privilege = "dummyPrivilege";

        $result = $dsDoctor->privilegeExists($privilege);
        $this->assertFalse($result);
    }

    public function testGetPrivilege(): void
    {
        $privilege = "selfAccountRead";

        $dsDoctor = $this->instanciate();

        $result = $dsDoctor->getPrivilege($privilege);
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testSetPrivilege(): void
    {
        try {
            $privilege = "selfAccountRead";

            $dsDoctor = $this->instanciate();

            $dsDoctor->setPrivilege($privilege, false);

            $result = $dsDoctor->getPrivilege($privilege);
            $this->assertIsBool($result);
            $this->assertFalse($result);
        } finally {
            $dsDoctor->setPrivilege($privilege, true);
        }
    }
}
