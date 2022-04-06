<?php

namespace Tests\DataStructures\User;

use Faker\Factory;
use Faker\Generator;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;
use TheClinicDataStructures\DataStructures\Order\DSOrders;
use TheClinicDataStructures\DataStructures\User\DSSecretary;
use TheClinicDataStructures\DataStructures\User\DSUser;
use TheClinicDataStructures\DataStructures\User\ICheckAuthentication;
use TheClinicDataStructures\DataStructures\Visit\DSVisits;

class DSSecretaryTest extends TestCase
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

    private function instanciate(): DSSecretary
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
        return new DSSecretary(...$this->constructArgs);
    }

    public function test()
    {
        $dsSecretary = $this->instanciate();

        $this->iCheckAuthentication->shouldReceive("isAuthenticated")->with($dsSecretary)->andReturn(true);

        $this->assertEquals($this->id, $dsSecretary->getId());
        $this->assertEquals($this->firstname, $dsSecretary->getFirstname());
        $this->assertEquals($this->lastname, $dsSecretary->getLastname());
        $this->assertEquals($this->username, $dsSecretary->getUsername());
        $this->assertEquals($this->gender, $dsSecretary->getGender());
        $this->assertEquals($this->email, $dsSecretary->getEmail());
        $this->assertEquals($this->emailVerifiedAt, $dsSecretary->getEmailVerifiedAt());
        $this->assertEquals($this->phonenumber, $dsSecretary->getPhonenumber());
        $this->assertEquals($this->phonenumberVerifiedAt, $dsSecretary->getPhonenumberVerifiedAt());
        $this->assertEquals($this->orders, $dsSecretary->orders);
        $this->assertEquals($this->createdAt, $dsSecretary->getCreatedAt());
        $this->assertEquals($this->updatedAt, $dsSecretary->getUpdatedAt());

        $this->assertEquals(true, $dsSecretary->isAuthenticated());
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
        $this->assertEquals("secretary", $this->instanciate()->getRuleName());
    }

    public function testGetUserPrivileges(): void
    {
        $orignalPrivileges = json_decode(file_get_contents(DSUser::PRIVILEGES_PATH . "/secretaryPrivileges.json"), true);
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
        $dsSecretary = $this->instanciate();

        $privilege = "selfAccountRead";

        $result = $dsSecretary->privilegeExists($privilege);
        $this->assertTrue($result);

        $privilege = "dummyPrivilege";

        $result = $dsSecretary->privilegeExists($privilege);
        $this->assertFalse($result);
    }

    public function testGetPrivilege(): void
    {
        $privilege = "selfAccountRead";

        $dsSecretary = $this->instanciate();

        $result = $dsSecretary->getPrivilege($privilege);
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }
}
