<?php

namespace TheClinicDataStructures\DataStructures\Order\Laser;

use TheClinicDataStructures\DataStructures\Order\DSOrder;
use TheClinicDataStructures\DataStructures\Order\DSOrders;
use TheClinicDataStructures\Exceptions\DataStructures\Order\InvalidValueTypeException;

class DSLaserOrders extends DSOrders
{
    /**
     * @param \TheClinicDataStructures\DataStructures\Order\Laser\DSLaserOrder $order
     * @return void
     * 
     * @throws \TheClinicDataStructures\Exceptions\DataStructures\Order\InvalidValueTypeException
     */
    protected function checkOrderType(DSOrder $order): void
    {
        if (!($order instanceof DSLaserOrder)) {
            throw new InvalidValueTypeException("This data structure only accepts the type: " . DSLaserOrder::class . " as an array member.", 500);
        }
    }
}
