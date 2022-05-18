<?php

namespace TheClinicDataStructures\DataStructures\User;

final class DSAdmin extends DSUser
{
    public function getRuleName(): string
    {
        return 'admin';
    }

    public function getUserPrivileges(): array
    {
        return include self::PRIVILEGES_PATH . "/adminPrivileges.php";
    }

    /**
     * @return array<string, mixed>
     */
    static public function getUserPrivilegesStatically(): array
    {
        return include self::PRIVILEGES_PATH . "/adminPrivileges.php";
    }
}
