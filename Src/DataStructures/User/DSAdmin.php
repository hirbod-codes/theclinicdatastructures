<?php

namespace TheClinicDataStructures\DataStructures\User;

final class DSAdmin extends DSUser
{
    public function getRuleName(): string
    {
        return 'admin';
    }

    public static function getUserPrivileges(string $roleName = ""): array
    {
        return include self::PRIVILEGES_PATH . "/adminPrivileges.php";
    }

    public function setPrivilege(string $privilege, mixed $value, IPrivilege $p): void
    {
        throw new StrictPrivilegeException('This role privileges are strict.', 403);
    }
}
