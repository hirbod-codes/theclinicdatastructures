<?php

namespace TheClinicDataStructures\DataStructures\Order\Laser;

use TheClinicDataStructures\DataStructures\User\DSUser;
use TheClinicDataStructures\DataStructures\Order\DSOrder;
use TheClinicDataStructures\DataStructures\Order\DSParts;
use TheClinicDataStructures\DataStructures\Order\DSPackages;
use TheClinicDataStructures\DataStructures\Visit\DSVisits;
use TheClinicDataStructures\Exceptions\DataStructures\Order\InvalidGenderException;

class DSLaserOrder extends DSOrder
{
    private DSParts $parts;

    private DSPackages $packages;

    public function __construct(
        int $id,
        DSUser $user,
        DSParts $parts,
        DSPackages $packages,
        ?DSVisits $visits = null,
        int $price,
        int $neededTime,
        \DateTime $createdAt,
        \DateTime $updatedAt
    ) {
        parent::__construct(
            $id,
            $user,
            $visits,
            $price,
            $neededTime,
            $createdAt,
            $updatedAt
        );

        $this->setParts($parts);
        $this->setPackages($packages);
    }

    public function getParts(): DSParts
    {
        return $this->parts;
    }

    public function setParts(DSParts $parts): void
    {
        if ((isset($this->packages) && $this->packages->getGender() !== $parts->getGender()) ||
            ($parts->getGender() !== $this->user->getGender())
        ) {
            throw new InvalidGenderException("Parts gender doesn't match with this data structures' order or package gender.", 500);
        }

        $this->parts = $parts;
    }

    public function getPackages(): DSPackages
    {
        return $this->packages;
    }

    public function setPackages(DSPackages $packages): void
    {
        if ((isset($this->parts) && $packages->getGender() !== $this->parts->getGender()) ||
            ($packages->getGender() !== $this->user->getGender())
        ) {
            throw new InvalidGenderException("Packages gender doesn't match with this data structures' order or part gender.", 500);
        }

        $this->packages = $packages;
    }
}
