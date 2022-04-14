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
use TheClinicDataStructures\DataStructures\User\Interfaces\IPrivilege;
use TheClinicDataStructures\Exceptions\DataStructures\User\StrictPrivilegeException;

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
        $this->constructArgs['iCheckAuthentication'] = $this->iCheckAuthentication;
        $this->constructArgs['orders'] = $this->orders;

        foreach (DSSecretary::getAttributes() as $attribute) {
            $this->constructArgs[$attribute] = $this->{$attribute};
        }

        return new DSSecretary(...$this->constructArgs);
    }

    public function test()
    {
        $dsSecretary = $this->instanciate();

        foreach (DSSecretary::getAttributes() as $attribute) {
            $this->assertEquals($this->{$attribute}, $dsSecretary->{'get' . ucfirst($attribute)}());
        }

        $this->assertEquals($this->orders, $dsSecretary->orders);

        $this->iCheckAuthentication->shouldReceive("isAuthenticated")->with($dsSecretary)->andReturn(true);
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
        $orignalPrivileges = require DSUser::PRIVILEGES_PATH . "/secretaryPrivileges.php";
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

    public function testSetPrivilege(): void
    {
        $privilege = "selfAccountRead";

        $dsSecretary = $this->instanciate();

        /** @var IPrivilege|MockInterface $iPrivilege */
        $iPrivilege = Mockery::mock(IPrivilege::class);
        $iPrivilege
            ->shouldReceive('setPrivilege')
            ->with(
                $dsSecretary,
                $privilege,
                false
            )
            // 
        ;

        try {
            $result = $dsSecretary->setPrivilege($privilege, false, $iPrivilege);
            throw new \RuntimeException('Failure!!!', 500);
        } catch (StrictPrivilegeException $th) {
        }

        $privilege = "selfAccountUpdateFirstname";

        $iPrivilege
            ->shouldReceive('setPrivilege')
            ->with(
                $dsSecretary,
                $privilege,
                false
            )
            // 
        ;

        $result = $dsSecretary->setPrivilege($privilege, false, $iPrivilege);
        $this->assertNull($result);
    }
}
