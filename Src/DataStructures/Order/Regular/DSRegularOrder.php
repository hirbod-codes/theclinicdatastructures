<?php

namespace TheClinicDataStructures\DataStructures\Order\Regular;

use TheClinicDataStructures\DataStructures\Order\DSOrder;
use TheClinicDataStructures\DataStructures\Visit\DSVisits;
use TheClinicDataStructures\DataStructures\Visit\Regular\DSRegularVisits;
use TheClinicDataStructures\Exceptions\DataStructures\Order\InvalidValueTypeException;

class DSRegularOrder extends DSOrder
{
    protected function validateVisitsType(DSVisits|null $visits): void
    {
        if ($visits === null) {
            return;
        }

        if (!($visits instanceof DSRegularVisits)) {
            throw new InvalidValueTypeException("This data structure only accepts the type: " . DSRegularVisits::class . " as it's associated visits.", 500);
        }
    }
}
