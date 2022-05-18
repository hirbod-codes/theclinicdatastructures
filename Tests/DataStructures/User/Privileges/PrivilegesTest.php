<?php

namespace Tests\DataStructures\User;

use Faker\Factory;
use Faker\Generator;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;
use TheClinicDataStructures\DataStructures\User\DSAdmin;
use TheClinicDataStructures\DataStructures\User\DSDoctor;
use TheClinicDataStructures\DataStructures\User\DSOperator;
use TheClinicDataStructures\DataStructures\User\DSPatient;
use TheClinicDataStructures\DataStructures\User\DSSecretary;
use TheClinicDataStructures\DataStructures\User\DSUser;

class PrivilegesTest extends TestCase
{
    private Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
    }

    public function testPrivileges(): void
    {
        file_put_contents(__DIR__ . '/DSAdmin.log', json_encode(DSAdmin::getAttributes(), JSON_PRETTY_PRINT));
        file_put_contents(__DIR__ . '/DSDoctor.log', json_encode(DSDoctor::getAttributes(), JSON_PRETTY_PRINT));
        file_put_contents(__DIR__ . '/DSSecretary.log', json_encode(DSSecretary::getAttributes(), JSON_PRETTY_PRINT));
        file_put_contents(__DIR__ . '/DSOperator.log', json_encode(DSOperator::getAttributes(), JSON_PRETTY_PRINT));
        file_put_contents(__DIR__ . '/DSPatient.log', json_encode(DSPatient::getAttributes(), JSON_PRETTY_PRINT));
        $privilegesFile = __DIR__ . '/../../../../Src/DataStructures/User/Privileges/privileges.php';
        $referencePrivileges = include($privilegesFile);
        file_put_contents(__DIR__ . '/privileges.log', json_encode($referencePrivileges, JSON_PRETTY_PRINT));

        foreach (DSUser::$roles as $role) {
            if (!is_file($flieName = __DIR__ . '/../../../../Src/DataStructures/User/Privileges/' . $role . 'Privileges.php')) {
                continue;
            }

            $specialPrivileges = include($flieName);
            file_put_contents(__DIR__ . '/' . $role . '.log', json_encode($specialPrivileges, JSON_PRETTY_PRINT));

            foreach (array_keys($specialPrivileges) as $specialPrivilege) {
                $this->assertTrue(in_array($specialPrivilege, $referencePrivileges), 'The key: ' . $specialPrivilege);
            }
        }
    }
}
