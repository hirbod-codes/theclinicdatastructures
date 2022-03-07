<?php

namespace TheClinicDataStructures\DataStructures\Order\Laser;

use TheClinicDataStructures\DataStructures\User\DSUser;
use TheClinicDataStructures\DataStructures\Order\DSOrder;
use TheClinicDataStructures\DataStructures\Order\DSParts;
use TheClinicDataStructures\DataStructures\Order\DSPackages;
use TheClinicDataStructures\DataStructures\Visit\DSVisits;
use TheClinicDataStructures\DataStructures\Visit\Laser\DSLaserVisits;
use TheClinicDataStructures\Exceptions\DataStructures\Order\InvalidGenderException;
use TheClinicDataStructures\Exceptions\DataStructures\Order\InvalidValueTypeException;

class DSLaserOrder extends DSOrder
{
    private DSParts|null $parts;

    private DSPackages|null $packages;

    public function __construct(
        int $id,
        DSUser $user,
        DSParts|null $parts = null,
        DSPackages|null $packages = null,
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

        if ($parts === null && $packages === null) {
            throw new \RuntimeException("Atleast one of the parts or packages must be provided.", 500);
        }

        $this->setParts($parts);
        $this->setPackages($packages);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user->toArray(),
            'parts' => $this->parts === null ? null : $this->parts->toArray(),
            'packages' => $this->packages === null ? null : $this->packages->toArray(),
            'price' => $this->price,
            'neededTime' => $this->neededTime,
            'visits' => $this->visits === null ? null : $this->visits->toArray(),
            'createdAt' => $this->createdAt->format("Y-m-d H:i:s"),
            'updatedAt' => $this->updatedAt->format("Y-m-d H:i:s"),
        ];
    }

    protected function validateVisitsType(DSVisits|null $visits): void
    {
        if ($visits === null) {
            return;
        }

        if (!($visits instanceof DSLaserVisits)) {
            throw new InvalidValueTypeException("This data structure only accepts the type: " . DSLaserVisits::class . " as it's associated visits.", 500);
        }
    }

    public function getParts(): DSParts
    {
        return $this->parts;
    }

    public function setParts(DSParts|null $parts): void
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

    public function setPackages(DSPackages|null $packages): void
    {
        if ((isset($this->parts) && $packages->getGender() !== $this->parts->getGender()) ||
            ($packages->getGender() !== $this->user->getGender())
        ) {
            throw new InvalidGenderException("Packages gender doesn't match with this data structures' order or part gender.", 500);
        }

        $this->packages = $packages;
    }
}
