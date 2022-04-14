<?php

namespace TheClinicDataStructures\DataStructures\User\Interfaces;

use TheClinicDataStructures\DataStructures\User\DSUser;

interface IPrivilege
{
    public function setPrivilege(DSUser $user, string $privilege, mixed $value): void;
}
