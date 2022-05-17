<?php

namespace TheClinicDataStructures\DataStructures\User;

final class DSSecretary extends DSUser
{
    public function getRuleName(): string
    {
        return 'secretary';
    }

    public function getUserPrivileges(): array
    {
        return include self::PRIVILEGES_PATH . "/secretaryPrivileges.php";
    }
}
