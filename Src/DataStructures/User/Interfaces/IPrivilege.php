<?php

namespace TheClinicDataStructures\DataStructures\User\Interfaces;

use TheClinicDataStructures\DataStructures\User\DSUser;

interface IPrivilege
{
    /**
     * @return array<string, string> ['privilege' => value, ...]
     */
    public function getUserPrivileges(DSUser $dsUser): array;
}
