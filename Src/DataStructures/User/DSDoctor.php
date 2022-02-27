<?php

namespace TheClinicDataStructures\DataStructures\User;

use TheClinicDataStructures\Exceptions\DataStructures\User\NoPrivilegeFoundException;

class DSDoctor extends DSUser
{
    public function getRuleName(): string
    {
        return 'doctor';
    }

    public function getUserPrivileges(): array
    {
        return json_decode(file_get_contents(__DIR__ . "/Privileges/doctorPrivileges.json"), true);
    }

    public function getPrivilege(string $privilege): mixed
    {
        if (!$this->privilegeExists($privilege)) {
            throw new NoPrivilegeFoundException();
        }

        $privileges = json_decode(file_get_contents(__DIR__ . "/Privileges/doctorPrivileges.json"), true);

        foreach ($privileges as $p => $pVal) {
            if ($p === $privilege) {
                return $pVal;
            }
        }

        throw new NoPrivilegeFoundException();
    }

    public function setPrivilege(string $privilege, mixed $value): void
    {
        $privileges = json_decode(file_get_contents(__DIR__ . "/Privileges/doctorPrivileges.json"), true);

        if (!isset($privileges[$privilege])) {
            throw new NoPrivilegeFoundException();
        }

        $privileges[$privilege] = $value;

        if (file_put_contents(__DIR__ . "/doctorPrivileges.json", $privileges) === false) {
            throw new \LogicException("Failed to set the privilege!", 500);
        }
    }

    public function privilegeExists(string $privilege): bool
    {
        $privileges = $this->getPrivileges();

        foreach ($privileges as $p) {
            if ($p === $privilege) {
                return true;
            }
        }

        return false;
    }
}