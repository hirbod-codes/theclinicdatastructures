<?php

namespace TheClinicDataStructures\DataStructures\User;

interface IUserRule
{
    public function getRuleName(): string;
    
    public function privilegeExists(DSUser $user, string $privilege): bool;

    /**
     * @return array [ "privilege_name" => privilege_value, ... ]
     */
    public function getPrivileges(DSUser $user): array;
    
    public function getPrivilegeValue(DSUser $user, string $privilege): mixed;

    public function setPrivilege(DSUser $user, string $privilege): void;
}
