<?php

namespace TheClinicDataStructures\DataStructures\User\Interfaces;

use TheClinicDataStructures\DataStructures\User\DSUser;

interface IPrivilege
{
    public function setPrivilege(DSUser $dsUser, string $privilege, mixed $value): void;

    /**
     * @return array<string, string> ['privilege' => value, ...]
     */
    public function getUserPrivileges(DSUser $dsUser): array;
}
