<?php

namespace TheClinicDataStructures\DataStructures\User;

class DSPatient extends DSUser
{
    public function getRuleName(): string
    {
        return 'patient';
    }

    public static function getUserPrivileges(): array
    {
        return include self::PRIVILEGES_PATH . "/patientPrivileges.php";
    }
}
