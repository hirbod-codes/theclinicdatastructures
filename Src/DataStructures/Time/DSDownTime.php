<?php

namespace TheClinicDataStructures\DataStructures\Time;

use TheClinicDataStructures\DataStructures\Time\DSDateTimePeriod;

class DSDownTime extends DSDateTimePeriod
{
    public string $name;

    public function cloneIt(): DSDownTime
    {
        return new DSDownTime((new \DateTime())->setTimestamp($this->start->getTimestamp()), (new \DateTime())->setTimestamp($this->end->getTimestamp()));
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'start' => $this->getStart()->format("Y-m-d H:i:s"),
            'end' => $this->getEnd()->format("Y-m-d H:i:s"),
        ];
    }
}
