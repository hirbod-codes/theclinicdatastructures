<?php

use TheClinicDataStructures\DataStructures\User\DSDoctor;

$doctorPrivileges = [];
foreach (DSDoctor::getAttributes() as $attribute => $types) {
    foreach ($types as $type) {
        if (strpos($type, 'TheClinicDataStructures') !== false) {
            continue 2;
        }
    }

    $doctorPrivileges['accountUpdate' . ucfirst($attribute)] = true;
    $doctorPrivileges['selfAccountUpdate' . ucfirst($attribute)] = true;
}

$privileges = [
    "accountsRead" => false,
    "accountRead" => false,
    "selfAccountRead" => true,

    "accountCreate" => true,

    "accountDelete" => false,
    "selfAccountDelete" => true,

    "accountUpdate" => false,
    "selfAccountUpdate" => true,


    "selfLaserOrdersRead" => true,
    "selfLaserOrderCreate" => true,
    "selfLaserOrderDelete" => true,

    "selfRegularOrdersRead" => true,
    "selfRegularOrderCreate" => true,
    "selfRegularOrderDelete" => true,

    "regularOrdersRead" => true,
    "regularOrderCreate" => true,
    "regularOrderDelete" => false,

    "laserOrderCreate" => true,
    "laserOrderDelete" => false,
    "laserOrdersRead" => true,


    "selfLaserVisitRetrieve" => true,
    "selfLaserVisitCreate" => true,
    "selfLaserVisitDelete" => true,

    "selfRegularVisitRetrieve" => true,
    "selfRegularVisitCreate" => true,
    "selfRegularVisitDelete" => true,

    "laserVisitRetrieve" => true,
    "laserVisitCreate" => true,
    "laserVisitDelete" => false,

    "regularVisitRetrieve" => true,
    "regularVisitCreate" => true,
    "regularVisitDelete" => false,
];

$privileges = array_merge($privileges, $doctorPrivileges);

return $privileges;
