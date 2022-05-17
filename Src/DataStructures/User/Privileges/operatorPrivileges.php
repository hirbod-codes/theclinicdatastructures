<?php

use TheClinicDataStructures\DataStructures\User\DSOperator;

$operatorPrivileges = [];
foreach (DSOperator::getAttributes() as $attribute => $types) {
    $operatorPrivileges['accountUpdate' . ucfirst($attribute)] = false;
    $operatorPrivileges['selfAccountUpdate' . ucfirst($attribute)] = false;
}

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

$privileges = array_merge($privileges, $operatorPrivileges);

return $privileges;
