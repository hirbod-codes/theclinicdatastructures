<?php

namespace TheClinicDataStructures\DataStructures\Order\Regular;

use TheClinicDataStructures\DataStructures\Order\DSOrder;
use TheClinicDataStructures\DataStructures\Order\DSOrders;
use TheClinicDataStructures\Exceptions\DataStructures\Order\InvalidValueTypeException;

class DSRegularOrders extends DSOrders
{
    /**
     * @param \TheClinicDataStructures\DataStructures\Order\Regular\DSRegularOrder $order
     * @return void
     * 
     * @throws \TheClinicDataStructures\Exceptions\DataStructures\Order\InvalidValueTypeException
     */
    protected function checkOrderType(DSOrder $order): void
    {
        if (!($order instanceof DSRegularOrder)) {
            throw new InvalidValueTypeException("This data structure only accepts the type: " . DSRegularOrder::class . " as an array member.", 500);
        }
    }
}
