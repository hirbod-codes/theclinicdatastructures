<?php

namespace TheClinicDataStructures\DataStructures\User;

use TheClinicDataStructures\Exceptions\DataStructures\User\NoPrivilegeFoundException;

class DSAdmin extends DSUser
{
    public function getRuleName(): string
    {
        return 'admin';
    }

    public static function getUserPrivileges(): array
    {
        return json_decode(file_get_contents(self::PRIVILEGES_PATH . "/adminPrivileges.json"), true);
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
        // This role has strict privileges.
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
