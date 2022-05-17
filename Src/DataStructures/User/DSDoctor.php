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
}
