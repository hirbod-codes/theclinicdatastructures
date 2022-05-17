<?php

namespace TheClinicDataStructures\DataStructures\User;

use TheClinicDataStructures\DataStructures\Order\DSOrders;
use TheClinicDataStructures\DataStructures\Interfaces\Arrayable;
use TheClinicDataStructures\DataStructures\Traits\IsArrayable;
use TheClinicDataStructures\DataStructures\User\ICheckAuthentication;
use TheClinicDataStructures\DataStructures\User\Interfaces\IPrivilege;
use TheClinicDataStructures\Exceptions\DataStructures\User\NoPrivilegeFoundException;
use TheClinicDataStructures\Exceptions\DataStructures\User\StrictPrivilegeException;

/**
 * The reason that these methods: getRuleName, getUserPrivileges, getPrivilege, privilegeExists, setPrivilege, getPrivileges are npt static,
 * is because every instance of this class might have their own ruleName or privileges.(custom rules)
 */
abstract class DSUser implements Arrayable, \Stringable
{
    use IsArrayable;

    const PRIVILEGES_PATH = __DIR__ . "/Privileges";

    public static array $roles = ['admin', 'doctor', 'secretary', 'operator', 'patient', 'custom_doctor', 'custom_secretary', 'custom_operator', 'custom_patient', 'custom'];

    protected static array $excludedPropertiesNames = ['iCheckAuthentication', 'iPrivilege'];

    private IPrivilege $iPrivilege;

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

    public DSOrders|null $orders;

    private \DateTime $createdAt;

    private \DateTime $updatedAt;

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
        string|null $email = null,
        \DateTime|null $emailVerifiedAt = null,
        DSOrders|null $orders = null,
    ) {
        $this->iPrivilege = $iPrivilege;
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
        $this->orders = $orders;
        $this->setCreatedAt($createdAt);
        $this->setUpdatedAt($updatedAt);
    }

    // ------------- \Stringable ----------------------------------------------------

    public function __toString(): string
    {
        return json_encode($this->toArray());
    }

    public static function getExcludedPropertiesNames()
    {
        if (!in_array('roles', self::$excludedPropertiesNames)) {
            self::$excludedPropertiesNames[] = 'roles';
        } elseif (!in_array('excludedPropertiesNames', self::$excludedPropertiesNames)) {
            self::$excludedPropertiesNames[] = 'excludedPropertiesNames';
        }

        return self::$excludedPropertiesNames;
    }

    public function isAuthenticated(): bool
    {
        return $this->iCheckAuthentication->isAuthenticated($this);
    }

    abstract public function getRuleName(): string;

    /**
     * @return array<string, mixed>
     */
    abstract public function getUserPrivileges(): array;

    public function getPrivilege(string $privilege): mixed
    {
        if (!$this->privilegeExists($privilege)) {
            throw new NoPrivilegeFoundException();
        }

        $privileges = static::getUserPrivileges();

        foreach ($privileges as $p => $pVal) {
            if ($p === $privilege) {
                return $pVal;
            }
        }

        throw new NoPrivilegeFoundException();
    }

    public function privilegeExists(string $privilege): bool
    {
        $privileges = $this->getUserPrivileges();

        foreach ($privileges as $p => $v) {
            if ($p === $privilege) {
                return true;
            }
        }

        return false;
    }

    public function setPrivilege(string $privilege, mixed $value): void
    {
        throw new StrictPrivilegeException('This role privileges are strict.', 403);
    }

    /**
     * @return string[]
     */
    public function getPrivileges(): array
    {
        return require self::PRIVILEGES_PATH . '/privileges.php';
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
