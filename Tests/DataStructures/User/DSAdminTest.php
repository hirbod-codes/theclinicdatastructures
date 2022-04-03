<?php

namespace Tests\DataStructures\User;

use Faker\Factory;
use Faker\Generator;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;
use TheClinicDataStructures\DataStructures\Order\DSOrders;
use TheClinicDataStructures\DataStructures\User\DSAdmin;
use TheClinicDataStructures\DataStructures\User\DSUser;
use TheClinicDataStructures\DataStructures\User\ICheckAuthentication;
use TheClinicDataStructures\DataStructures\Visit\DSVisits;

class DSAdminTest extends TestCase
{
    private Generator $faker;

    private $iCheckAuthentication;

    private $id;

    private string $firstname;

    private string $lastname;

    private string $username;

    private string $gender;

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
        $this->orders = null;
        $this->createdAt = new \DateTime;
        $this->updatedAt = new \DateTime;
    }

    private function instanciate(): DSAdmin
    {
        $this->constructArgs = [
            'iCheckAuthentication' => $this->iCheckAuthentication,
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'username' => $this->username,
            'gender' => $this->gender,
            'email' => $this->email,
            'emailVerifiedAt' => $this->emailVerifiedAt,
            'phonenumber' => $this->phonenumber,
            'phonenumberVerifiedAt' => $this->phonenumberVerifiedAt,
            'orders' => $this->orders,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt
        ];
        return new DSAdmin(...$this->constructArgs);
    }

    public function test()
    {
        $dsAdmin = $this->instanciate();

        $this->assertEquals($this->id, $dsAdmin->getId());
        $this->assertEquals($this->firstname, $dsAdmin->getFirstname());
        $this->assertEquals($this->lastname, $dsAdmin->getLastname());
        $this->assertEquals($this->username, $dsAdmin->getUsername());
        $this->assertEquals($this->gender, $dsAdmin->getGender());
        $this->assertEquals($this->email, $dsAdmin->getEmail());
        $this->assertEquals($this->emailVerifiedAt, $dsAdmin->getEmailVerifiedAt());
        $this->assertEquals($this->phonenumber, $dsAdmin->getPhonenumber());
        $this->assertEquals($this->phonenumberVerifiedAt, $dsAdmin->getPhonenumberVerifiedAt());
        $this->assertEquals($this->orders, $dsAdmin->orders);
        $this->assertEquals($this->createdAt, $dsAdmin->getCreatedAt());
        $this->assertEquals($this->updatedAt, $dsAdmin->getUpdatedAt());

        $this->iCheckAuthentication->shouldReceive("isAuthenticated")->with($dsAdmin)->andReturn(true);

        $this->assertEquals(true, $dsAdmin->isAuthenticated());
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
        $this->assertEquals("admin", $this->instanciate()->getRuleName());
    }

    public function testGetUserPrivileges(): void
    {
        $orignalPrivileges = json_decode(file_get_contents(DSUser::PRIVILEGES_PATH . "/adminPrivileges.json"), true);
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
        $dsAdmin = $this->instanciate();

        $privilege = "selfAccountRead";

        $result = $dsAdmin->privilegeExists($privilege);
        $this->assertTrue($result);

        $privilege = "dummyPrivilege";

        $result = $dsAdmin->privilegeExists($privilege);
        $this->assertFalse($result);
    }

    public function testGetPrivilege(): void
    {
        $privilege = "selfAccountRead";

        $dsAdmin = $this->instanciate();

        $result = $dsAdmin->getPrivilege($privilege);
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testSetPrivilege(): void
    {
        try {
            $privilege = "selfAccountRead";

            $dsAdmin = $this->instanciate();

            $dsAdmin->setPrivilege($privilege, false);

            $result = $dsAdmin->getPrivilege($privilege);
            $this->assertIsBool($result);
            $this->assertFalse($result);
        } finally {
            $dsAdmin->setPrivilege($privilege, true);
        }
    }
}
