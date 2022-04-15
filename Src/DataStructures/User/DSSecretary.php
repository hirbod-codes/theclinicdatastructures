<?php

namespace TheClinicDataStructures\DataStructures\User;

class DSSecretary extends DSUser
{
    public function getRuleName(): string
    {
        return 'secretary';
    }

    public static function getUserPrivileges(): array
    {
        return include self::PRIVILEGES_PATH . "/secretaryPrivileges.php";
    }
}
