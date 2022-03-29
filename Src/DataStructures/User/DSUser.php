<?php

namespace TheClinicDataStructures\DataStructures\User;

use TheClinicDataStructures\DataStructures\Order\DSOrders;
use TheClinicDataStructures\DataStructures\Visit\DSVisits;
use TheClinicDataStructures\DataStructures\User\ICheckAuthentication;

abstract class DSUser
{
    const PRIVILEGES_PATH = __DIR__ . "/Privileges";

    public static array $roles = ['admin', 'doctor', 'secretary', 'operator', 'patient'];

    private ICheckAuthentication $iCheckAuthentication;

    private int $id;

    private string $firstname;

    private string $lastname;

    private string $username;

    private string $gender;

    public string|null $email;

    public \DateTime|null $emailVerifiedAt;

    private string $phonenumber;

    private \DateTime $phonenumberVerifiedAt;

    public DSVisits|null $visits;

    public DSOrders|null $orders;

    private \DateTime $createdAt;

    private \DateTime $updatedAt;

    public function __construct(
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
        DSVisits|null $visits = null,
        DSOrders|null $orders = null,
    ) {
        $this->iCheckAuthentication = $iCheckAuthentication;
        $this->setId($id);
        $this->setFirstname($firstname);
        $this->setLastname($lastname);
        $this->setUsername($username);
        $this->setGender($gender);
        $this->email = $email;
        $this->emailVerifiedAt = $emailVerifiedAt;
        $this->setPhonenumber($phonenumber);
        $this->setPhonenumberVerifiedAt($phonenumberVerifiedAt);
        $this->visits = $visits;
        $this->orders = $orders;
        $this->setCreatedAt($createdAt);
        $this->setUpdatedAt($updatedAt);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'username' => $this->username,
            'gender' => $this->gender,
            'email' => $this->email,
            'emailVerifiedAt' => $this->emailVerifiedAt->format("Y-m-d H:i:s"),
            'phonenumber' => $this->phonenumber,
            'phonenumberVerifiedAt' => $this->phonenumberVerifiedAt->format("Y-m-d H:i:s"),
            'visits' => $this->visits === null ? null : $this->visits->toArray(),
            'orders' => $this->orders === null ? null : $this->orders->toArray(),
            'createdAt' => $this->createdAt->format("Y-m-d H:i:s"),
            'updatedAt' => $this->updatedAt->format("Y-m-d H:i:s")
        ];
    }

    public function isAuthenticated(): bool
    {
        return $this->iCheckAuthentication->isAuthenticated($this);
    }

    abstract public function getRuleName(): string;

    abstract static public function getUserPrivileges(): array;

    abstract public function privilegeExists(string $privilege): bool;

    abstract public function getPrivilege(string $privilege): mixed;

    abstract public function setPrivilege(string $privilege, mixed $value): void;

    /**
     * @return string[]
     */
    public static function getPrivileges(): array
    {
        return json_decode(file_get_contents(self::PRIVILEGES_PATH . "/privileges.json"), true);
    }

    // id
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $var): void
    {
        $this->id = $var;
    }

    // firstname
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $var): void
    {
        $this->firstname = $var;
    }

    // lastname
    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $var): void
    {
        $this->lastname = $var;
    }

    // username
    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $var): void
    {
        $this->username = $var;
    }

    // gender
    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $var): void
    {
        $this->gender = $var;
    }

    // phonenumber
    public function getPhonenumber(): string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(string $phonenumber): void
    {
        $this->phonenumber = $phonenumber;
    }

    // phonenumberVerifiedAt
    public function getPhonenumberVerifiedAt(): \DateTime
    {
        return $this->phonenumberVerifiedAt;
    }

    public function setPhonenumberVerifiedAt(\DateTime $phonenumberVerifiedAt): void
    {
        $this->phonenumberVerifiedAt = $phonenumberVerifiedAt;
    }

    // Email
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    // EmailVerifiedAt
    public function getEmailVerifiedAt(): \DateTime
    {
        return $this->emailVerifiedAt;
    }

    public function setEmailVerifiedAt(\DateTime $emailVerifiedAt): void
    {
        $this->emailVerifiedAt = $emailVerifiedAt;
    }

    // createdAt
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $var): void
    {
        $this->createdAt = $var;
    }

    // updatedAt
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $var): void
    {
        $this->updatedAt = $var;
    }
}
