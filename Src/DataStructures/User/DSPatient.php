<?php

namespace TheClinicDataStructures\DataStructures\User;

use TheClinicDataStructures\DataStructures\Order\DSOrders;
use TheClinicDataStructures\DataStructures\User\Interfaces\IPrivilege;

final class DSPatient extends DSUser
{
    protected int $age;

    protected string $state;

    protected string $city;

    public string|null $address;

    public string|null $laserGrade;

    /**
     * @param ICheckAuthentication $iCheckAuthentication
     * @param IPrivilege $iPrivilege
     * @param integer $id
     * @param string $firstname
     * @param string $lastname
     * @param string $username
     * @param string $gender
     * @param string $phonenumber
     * @param \DateTime $phonenumberVerifiedAt
     * @param \DateTime $createdAt
     * @param \DateTime $updatedAt
     * @param integer $age
     * @param string $state
     * @param string $city
     * @param string|null $address
     * @param string|null $laserGrade
     * @param string|null $email
     * @param \DateTime|null $emailVerifiedAt
     * @param DSOrders|null $orders
     */
    public function __construct(
        ICheckAuthentication $iCheckAuthentication,
        IPrivilege $iPrivilege,
        int $id,
        string $firstname,
        string $lastname,
        string $username,
        string $gender,
        string $phonenumber,
        \DateTime $phonenumberVerifiedAt,
        \DateTime $createdAt,
        \DateTime $updatedAt,
        int $age,
        string $state,
        string $city,
        string|null $address = null,
        string|null $laserGrade = null,
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

        $this->setAge($age);
        $this->setState($state);
        $this->setCity($city);
        $this->address = $address;
        $this->laserGrade = $laserGrade;
    }

    public function getRuleName(): string
    {
        return 'patient';
    }

    public function getUserPrivileges(): array
    {
        return include self::PRIVILEGES_PATH . "/patientPrivileges.php";
    }

    /**
     * @return array<string, mixed>
     */
    static public function getUserPrivilegesStatically(): array
    {
        return include self::PRIVILEGES_PATH . "/patientPrivileges.php";
    }

    // age
    public function getAge(): string
    {
        return $this->age;
    }

    public function setAge(string $age): void
    {
        $this->age = $age;
    }

    // state
    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    // city
    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }
}
