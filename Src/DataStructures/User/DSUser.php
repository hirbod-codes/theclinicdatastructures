<?php

namespace TheClinicDataStructures\DataStructures\User;

use TheClinicDataStructures\DataStructures\Order\DSOrders;
use TheClinicDataStructures\DataStructures\User\ICheckAuthentication;
use TheClinicDataStructures\DataStructures\User\Interfaces\IPrivilege;
use TheClinicDataStructures\Exceptions\DataStructures\User\NoPrivilegeFoundException;
use TheClinicDataStructures\Exceptions\DataStructures\User\StrictPrivilegeException;

abstract class DSUser
{
    const PRIVILEGES_PATH = __DIR__ . "/Privileges";

    public static array $roles = ['admin', 'doctor', 'secretary', 'operator', 'patient'];

    /**
     * Includes properties that have scalar or null or array types and 
     * are not attributes of this data structure.
     * Any property with any other type except \DateTime will not be considered an attribute property.
     *
     * @var array
     */
    private array $specialProperties = [];

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

    public static function getAttributes(): array
    {
        $attributes = [];
        $properties = (new \ReflectionClass(self::class))->getProperties();

        /** @var \ReflectionProperty $property */
        foreach ($properties as $property) {
            if (in_array($property->getName(), ['specialProperties', 'roles']) || $property->isStatic()) {
                continue;
            }

            $propertyType = $property->getType();

            if ($propertyType instanceof \ReflectionNamedType) {
                if (in_array($propertyType->getName(), ['int', 'string', 'float', 'bool', 'array', 'null', \DateTime::class])) {
                    $attributes[] = $property->getName();
                }
            } elseif ($propertyType instanceof \ReflectionUnionType) {
                $isValid = true;
                /** @var \ReflectionNamedType $type */
                foreach ($propertyType->getTypes() as $type) {
                    if (!in_array($type->getName(), ['int', 'string', 'float', 'bool', 'array', 'null', \DateTime::class])) {
                        $isValid = false;
                    }
                }
                if ($isValid) {
                    $attributes[] = $property->getName();
                }
            }
        }

        return $attributes;
    }

    public function toArray(): array
    {
        $array = [];
        foreach (self::getAttributes() as $attribute) {
            if ($this->{$attribute} instanceof \DateTime) {
                $value = $this->{$attribute}->format('Y-m-d H:i:s');
            } else {
                $value = $this->{$attribute};
            }
            $array[$attribute] = $value;
        }

        $array['orders'] = $this->orders === null ? null : $this->orders->toArray();

        return $array;
    }

    public function isAuthenticated(): bool
    {
        return $this->iCheckAuthentication->isAuthenticated($this);
    }

    abstract public function getRuleName(): string;

    abstract static public function getUserPrivileges(): array;

    public function getPrivilege(string $privilege): mixed
    {
        if (!$this->privilegeExists($privilege)) {
            throw new NoPrivilegeFoundException();
        }

        $privileges = $this->getUserPrivileges();

        foreach ($privileges as $p => $pVal) {
            if ($p === $privilege) {
                return $pVal;
            }
        }

        throw new NoPrivilegeFoundException();
    }

    public function privilegeExists(string $privilege): bool
    {
        $privileges = $this->getPrivileges();

        foreach ($privileges as $p) {
            if ($p === $privilege) {
                return true;
            }
        }

        return false;
    }

    public function setPrivilege(string $privilege, mixed $value, IPrivilege $ip): void
    {
        // This role has strict privileges.

        if (!$this->privilegeExists($privilege)) {
            throw new NoPrivilegeFoundException();
        }

        $privileges = $this->getUserPrivileges();

        foreach ($privileges as $p => $value) {
            if ($p === $privilege) {
                throw new StrictPrivilegeException('this privilege is not volatile.', 500);
            }
        }

        $ip->setPrivilege($this, $privilege, $value);
    }

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
