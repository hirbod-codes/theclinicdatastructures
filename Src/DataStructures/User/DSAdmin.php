<?php

namespace TheClinicDataStructures\DataStructures\User;

use TheClinicDataStructures\DataStructures\User\Interfaces\IPrivilege;
use TheClinicDataStructures\Exceptions\DataStructures\User\StrictPrivilegeException;

class DSAdmin extends DSUser
{
    public function getRuleName(): string
    {
        return 'admin';
    }

    public static function getUserPrivileges(): array
    {
        return include self::PRIVILEGES_PATH . "/adminPrivileges.php";
    }

    public function setPrivilege(string $privilege, mixed $value, IPrivilege $p): void
    {
        throw new StrictPrivilegeException('This role privileges are strict.', 403);
    }
}
