<?php

namespace TheClinicDataStructures\DataStructures\User;

use TheClinicDataStructures\DataStructures\Order\DSOrders;
use TheClinicDataStructures\DataStructures\User\Interfaces\IPrivilege;

final class DSCustom extends DSUser
{
    private string $ruleName;

    /**
     * A JSON string of arbitrary data.
     *
     * @var string|null
     */
    public array|null $data;

    /**
     * @param string $ruleName
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
     * @param string|null|null $email
     * @param \DateTime|null|null $emailVerifiedAt
     * @param DSOrders|null|null $orders
     * @param string|null|null $data
     */
    public function __construct(
        string $ruleName,
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
        string|null $email = null,
        \DateTime|null $emailVerifiedAt = null,
        DSOrders|null $orders = null,
        array|null $data = null,
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

        $this->ruleName = $ruleName;
        $this->data = $data;
    }

    public function getRuleName(): string
    {
        return $this->ruleName;
    }

    public function getUserPrivileges(): array
    {
        return $this->iPrivilege->getUserPrivileges($this);
    }
}
