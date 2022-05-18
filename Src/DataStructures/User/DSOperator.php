<?php

namespace TheClinicDataStructures\DataStructures\User;

use TheClinicDataStructures\DataStructures\Order\DSOrders;
use TheClinicDataStructures\DataStructures\User\Interfaces\IPrivilege;

final class DSOperator extends DSUser
{
    public DSPatients|null $dsPatients;

    /**
     * @param IPrivilege $iPrivilege
     * @param ICheckAuthentication $iCheckAuthentication
     * @param integer $id
     * @param string $firstname
     * @param string $lastname
     * @param string $username
     * @param string $gender
     * @param string $phonenumber
     * @param \DateTime $phonenumberVerifiedAt
     * @param \DateTime $createdAt
     * @param \DateTime $updatedAt
     * @param DSPatients|null|null $dsPatients
     * @param string|null|null $email
     * @param \DateTime|null|null $emailVerifiedAt
     * @param DSOrders|null|null $orders
     */
    public function __construct(
        IPrivilege $iPrivilege,
        ICheckAuthentication $iCheckAuthentication,
        int $id,
        string $firstname,
        string $lastname,
        string $username,
        string $gender,
        string $phonenumber,
        \DateTime $phonenumberVerifiedAt,
        \DateTime $createdAt,
        \DateTime $updatedAt,
        DSPatients|null $dsPatients = null,
        string|null $email = null,
        \DateTime|null $emailVerifiedAt = null,
        DSOrders|null $orders = null,
    ) {
        parent::__construct(
            $iPrivilege,
            $iCheckAuthentication,
            $id,
            $firstname,
            $lastname,
            $username,
            $gender,
            $phonenumber,
            $phonenumberVerifiedAt,
            $createdAt,
            $updatedAt,
            $email,
            $emailVerifiedAt,
            $orders
        );

        $this->dsPatients = $dsPatients;
    }
    public function getRuleName(): string
    {
        return 'operator';
    }

    public function getUserPrivileges(): array
    {
        return include self::PRIVILEGES_PATH . "/operatorPrivileges.php";
    }

    /**
     * @return array<string, mixed>
     */
    static public function getUserPrivilegesStatically(): array
    {
        return include self::PRIVILEGES_PATH . "/operatorPrivileges.php";
    }
}
