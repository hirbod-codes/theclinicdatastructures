<?php

namespace TheClinicDataStructure\DataStructures\Order;

use TheClinicDataStructure\DataStructures\User\DSUser;

class DSLaserOrders extends DSOrders implements \ArrayAccess, \Iterator, \Countable
{
    public function __construct(DSUser|null $user = null, bool $mixedOrders = false)
    {
        parent::__construct($user, $mixedOrders);

        if ($mixedOrders) {
            $this->orderType = DSOrder::class;
        } else {
            $this->orderType = DSLaserOrder::class;
        }
    }
}
