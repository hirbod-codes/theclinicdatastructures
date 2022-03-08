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
        return json_decode(file_get_contents(self::PRIVILEGES_PATH . "/doctorPrivileges.json"), true);
    }

    public function getPrivilege(string $privilege): mixed
    {
        if (!$this->privilegeExists($privilege)) {
            throw new NoPrivilegeFoundException();
        }

        $privileges = $this->getUserPrivileges();

        foreach ($privileges as $p => $pVal) {
            if ($p === $privilege) {
                return $pVal;
            }
        }

        throw new NoPrivilegeFoundException();
    }

    public function setPrivilege(string $privilege, mixed $value): void
    {
        $privileges = $this->getUserPrivileges();

        if (!isset($privileges[$privilege])) {
            throw new NoPrivilegeFoundException();
        }

        $privileges[$privilege] = $value;

        if (file_put_contents(self::PRIVILEGES_PATH . "/doctorPrivileges.json", json_encode($privileges)) === false) {
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
