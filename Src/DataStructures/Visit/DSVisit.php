<?php

namespace TheClinicDataStructures\DataStructures\Visit;

use TheClinicDataStructures\DataStructures\Time\DSDateTimePeriod;
use TheClinicDataStructures\DataStructures\Order\DSOrder;
use TheClinicDataStructures\DataStructures\Time\DSWeekDaysPeriods;
use TheClinicDataStructures\DataStructures\User\DSUser;

class DSVisit
{
    private int $id;

    private DSUser $user;

    private DSOrder $order;

    private int $visitTimestamp;

    private int $consumingTime;

    public null|DSWeekDaysPeriods $weekDaysPeriods;

    public null|DSDateTimePeriod $dateTimePeriod;

    private \DateTime $createdAt;

    private \DateTime $updatedAt;

    /**
     * @param integer $id
     * @param DSUser $user
     * @param DSOrder $order
     * @param integer $visitTimestamp
     * @param integer $consumingTime
     * @param \DateTime $createdAt
     * @param \DateTime $updatedAt
     * @param DSWeekDaysPeriods|null|null $weekDaysPeriods must not have a value other than null at present of $dateTimePeriod.
     * @param DSDateTimePeriod|null|null $dateTimePeriod must not have a value other than null at present of $weekDaysPeriods.
     */
    public function __construct(
        int $id,
        DSUser $user,
        DSOrder $order,
        int $visitTimestamp,
        int $consumingTime,
        \DateTime $createdAt,
        \DateTime $updatedAt,
        DSWeekDaysPeriods|null $weekDaysPeriods = null,
        DSDateTimePeriod|null $dateTimePeriod = null,
    ) {
        if (!is_null($weekDaysPeriods) && !is_null($dateTimePeriod)) {
            throw new \LogicException("\$weekDaysPeriods and \$dateTimePeriod can't have a value beside null at the same time.", 500);
        }

        $this->id = $id;
        $this->user = $user;
        $this->order = $order;
        $this->visitTimestamp = $visitTimestamp;
        $this->consumingTime = $consumingTime;
        $this->weekDaysPeriods = $weekDaysPeriods;
        $this->dateTimePeriod = $dateTimePeriod;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user->toArray(),
            'order' => $this->order->toArray(),
            'visitTimestamp' => $this->visitTimestamp,
            'consumingTime' => $this->consumingTime,
            'weekDaysPeriods' => $this->weekDaysPeriods === null ? null : $this->weekDaysPeriods->toArray(),
            'dateTimePeriod' => $this->dateTimePeriod === null ? null : $this->dateTimePeriod->toArray(),
            'createdAt'=>$this->createdAt->format("Y-m-d H:i:s"),
            'updatedAt'=>$this->updatedAt->format("Y-m-d H:i:s")
        ];
    }

    public function setUser(DSUser $user): void
    {
        $this->user = $user;
    }

    public function setOrder(DSOrder $order): void
    {
        $this->order = $order;
    }

    public function setId(int $val): void
    {
        $this->id = $val;
    }

    public function setVisitTimestamp(int $val): void
    {
        $this->visitTimestamp = $val;
    }

    public function setConsumingTime(int $val): void
    {
        $this->consumingTime = $val;
    }

    public function setCreatedAt(\DateTime $val): void
    {
        $this->createdAt = $val;
    }

    public function setUpdatedAt(\DateTime $val): void
    {
        $this->updatedAt = $val;
    }

    public function getUser(): DSUser
    {
        return $this->user;
    }

    public function getOrder(): DSOrder
    {
        return $this->order;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getVisitTimestamp(): int
    {
        return $this->visitTimestamp;
    }

    public function getConsumingTime(): int
    {
        return $this->consumingTime;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}
