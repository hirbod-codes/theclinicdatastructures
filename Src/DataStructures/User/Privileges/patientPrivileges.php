<?php

$privileges = [
    "accountsRead" => false,
    "accountRead" => false,
    "selfAccountRead" => true,

    "accountCreate" => false,

    "accountDelete" => false,
    "selfAccountDelete" => true,

    "accountUpdate" => false,
    "selfAccountUpdate" => true,


    "selfLaserOrdersRead" => true,
    "selfLaserOrderCreate" => true,
    "selfLaserOrderDelete" => true,

    "regularOrdersRead" => false,
    "regularOrderCreate" => false,
    "regularOrderDelete" => false,

    "selfRegularOrdersRead" => true,
    "selfRegularOrderCreate" => true,
    "selfRegularOrderDelete" => true,

    "laserOrdersRead" => false,
    "laserOrderCreate" => false,
    "laserOrderDelete" => false,


    "selfLaserVisitRetrieve" => true,
    "selfLaserVisitCreate" => true,
    "selfLaserVisitDelete" => true,

    "laserVisitRetrieve" => false,
    "laserVisitCreate" => false,
    "laserVisitDelete" => false,

    "selfRegularVisitRetrieve" => true,
    "selfRegularVisitCreate" => true,
    "selfRegularVisitDelete" => true,

    "regularVisitRetrieve" => false,
    "regularVisitCreate" => false,
    "regularVisitDelete" => false,
];

return $privileges;
