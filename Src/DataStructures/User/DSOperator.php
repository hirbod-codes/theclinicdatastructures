<?php

namespace TheClinicDataStructures\DataStructures\User;

class DSOperator extends DSUser
{
    public function getRuleName(): string
    {
        return 'operator';
    }

    public static function getUserPrivileges(): array
    {
        return require self::PRIVILEGES_PATH . "/operatorPrivileges.php";
    }
}
