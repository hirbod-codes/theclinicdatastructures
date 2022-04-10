<?php

namespace TheClinicDataStructures\DataStructures\Visit\Regular;

use TheClinicDataStructures\DataStructures\Order\DSOrder;
use TheClinicDataStructures\DataStructures\Order\Regular\DSRegularOrder;
use TheClinicDataStructures\DataStructures\Visit\DSVisit;
use TheClinicDataStructures\DataStructures\Visit\DSVisits;
use TheClinicDataStructures\Exceptions\DataStructures\Visit\InvalidValueTypeException;

class DSRegularVisits extends DSVisits
{
    /**
     * @param \TheClinicDataStructures\DataStructures\Visit\Regular\DSRegularVisit $visit
     * @return void
     * 
     * @throws \TheClinicDataStructures\Exceptions\DataStructures\Visit\InvalidValueTypeException
     */
    protected function validateVisitType(DSVisit $visit): void
    {
        if (!($visit instanceof DSRegularVisit)) {
            throw new InvalidValueTypeException("The new member must be an object of class: " . DSRegularVisit::class, 500);
        }
    }
}
