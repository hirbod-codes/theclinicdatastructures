<?php

namespace TheClinicDataStructures\DataStructures\User;

final class DSDoctor extends DSUser
{
    public function getRuleName(): string
    {
        return 'doctor';
    }

    public function getUserPrivileges(): array
    {
        return include self::PRIVILEGES_PATH . "/doctorPrivileges.php";
    }

    /**
     * @return array<string, mixed>
     */
    static public function getUserPrivilegesStatically(): array
    {
        return include self::PRIVILEGES_PATH . "/doctorPrivileges.php";
    }
}
