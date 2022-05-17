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
use TheClinicDataStructures\DataStructures\User\Interfaces\IPrivilege;

class DSDoctorTest extends TestCase
{
    private Generator $faker;

    private $iPrivilege;

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

        /** @var IPrivilege|MockInterface $iCheckAuthentication */
        $this->iPrivilege = Mockery::mock(IPrivilege::class);

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

    private function instanciate(): DSDoctor
    {
        $this->constructArgs['iPrivilege'] = $this->iPrivilege;
        $this->constructArgs['iCheckAuthentication'] = $this->iCheckAuthentication;
        $this->constructArgs['orders'] = $this->orders;

        foreach (DSDoctor::getAttributes() as $attribute => $types) {
            $this->constructArgs[$attribute] = $this->{$attribute};
        }

        return new DSDoctor(...$this->constructArgs);
    }

    public function test()
    {
        $dsDoctor = $this->instanciate();

        foreach (DSDoctor::getAttributes() as $attribute => $types) {
            if (method_exists($dsDoctor, 'get' . ucfirst($attribute))) {
                $this->assertEquals($this->{$attribute}, $dsDoctor->{'get' . ucfirst($attribute)}());
            } else {
                $this->assertEquals($this->{$attribute}, $dsDoctor->{$attribute});
            }
        }

        $this->assertEquals($this->orders, $dsDoctor->orders);

        $this->iCheckAuthentication->shouldReceive("isAuthenticated")->with($dsDoctor)->andReturn(true);
        $this->assertEquals(true, $dsDoctor->isAuthenticated());
    }

    public function testToArray()
    {
        $dsAdminArray = $this->instanciate()->toArray();
        unset($this->constructArgs['iCheckAuthentication']);
        unset($this->constructArgs['iPrivilege']);

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
        $orignalPrivileges = include DSUser::PRIVILEGES_PATH . "/doctorPrivileges.php";
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
}
