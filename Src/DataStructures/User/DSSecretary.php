<?php

namespace TheClinicDataStructures\DataStructures\User;

final class DSSecretary extends DSUser
{
    public function getRuleName(): string
    {
        return 'secretary';
    }

    public static function getUserPrivileges(string $roleName = ""): array
    {
        return include self::PRIVILEGES_PATH . "/secretaryPrivileges.php";
    }
}
