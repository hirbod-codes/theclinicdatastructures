<?php

namespace TheClinicDataStructures\DataStructures\User;

use TheClinicDataStructures\DataStructures\User\Interfaces\IPrivilege;
use TheClinicDataStructures\Exceptions\DataStructures\User\StrictPrivilegeException;

class DSDoctor extends DSUser
{
    public function getRuleName(): string
    {
        return 'doctor';
    }

    public static function getUserPrivileges(): array
    {
        return require self::PRIVILEGES_PATH . "/doctorPrivileges.php";
    }

    public function setPrivilege(string $privilege, mixed $value, IPrivilege $p): void
    {
        throw new StrictPrivilegeException('This role privileges are strict.', 403);
    }
}
