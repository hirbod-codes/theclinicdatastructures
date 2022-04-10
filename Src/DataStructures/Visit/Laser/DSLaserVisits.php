<?php

namespace TheClinicDataStructures\DataStructures\Visit\Laser;

use TheClinicDataStructures\DataStructures\Order\DSOrder;
use TheClinicDataStructures\DataStructures\Order\Laser\DSLaserOrder;
use TheClinicDataStructures\DataStructures\Visit\DSVisit;
use TheClinicDataStructures\DataStructures\Visit\DSVisits;
use TheClinicDataStructures\Exceptions\DataStructures\Visit\InvalidValueTypeException;

class DSLaserVisits extends DSVisits
{
    /**
     * @param \TheClinicDataStructures\DataStructures\Visit\Laser\DSLaserVisit $visit
     * @return void
     * 
     * @throws \TheClinicDataStructures\Exceptions\DataStructures\Visit\InvalidValueTypeException
     */
    protected function validateVisitType(DSVisit $visit): void
    {
        if (!($visit instanceof DSLaserVisit)) {
            throw new InvalidValueTypeException("The new member must be an object of class: " . DSLaserVisit::class, 500);
        }
    }
}
