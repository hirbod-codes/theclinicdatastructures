<?php

use TheClinicDataStructures\DataStructures\User\DSAdmin;
use TheClinicDataStructures\DataStructures\User\DSDoctor;
use TheClinicDataStructures\DataStructures\User\DSOperator;
use TheClinicDataStructures\DataStructures\User\DSPatient;
use TheClinicDataStructures\DataStructures\User\DSSecretary;

$privileges = [
    "accountsRead",
    "accountRead",
    "selfAccountRead",

    "accountCreate",

    "accountDelete",
    "selfAccountDelete",

    "accountUpdate",
    "selfAccountUpdate",


    "selfLaserOrdersRead",
    "selfLaserOrderCreate",
    "selfLaserOrderDelete",

    "selfRegularOrdersRead",
    "selfRegularOrderCreate",
    "selfRegularOrderDelete",

    "regularOrdersRead",
    "regularOrderCreate",
    "regularOrderDelete",

    "laserOrderCreate",
    "laserOrderDelete",
    "laserOrdersRead",


    "selfLaserVisitRetrieve",
    "selfLaserVisitCreate",
    "selfLaserVisitDelete",

    "selfRegularVisitRetrieve",
    "selfRegularVisitCreate",
    "selfRegularVisitDelete",

    "laserVisitRetrieve",
    "laserVisitCreate",
    "laserVisitDelete",

    "regularVisitRetrieve",
    "regularVisitCreate",
    "regularVisitDelete",
];

$namespace = "TheClinicDataStructures\\DataStructures\\User\\";

/** @var string[] $attributes */
$attributes = [];
foreach (scandir(__DIR__ . '/../') as $value) {
    if (in_array($value, ['.', '..', 'ICheckAuthentication.php']) || is_dir(__DIR__ . '/../' . $value)) {
        continue;
    }
    $value = array_reverse(explode('/', str_replace("\\", '/', $value)))[0];
    $value = str_replace('.php', '', $value);

    $class = $namespace . $value;

    if (!class_exists($class)) {
        throw new \RuntimeException('Failure!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!', 500);
    }

    foreach ($class::getAttributes() as $attribute) {
        if (array_search($attribute, $attributes) === false) {
            $attributes[] = $attribute;
        }
    }
}

$updatePrivileges = [];
$selfUpdatePrivileges = [];

foreach ($attributes as $attribute) {
    $updatePrivileges[] = 'accountUpdate' . ucfirst($attribute);
    $selfUpdatePrivileges[] = 'selfAccountUpdate' . ucfirst($attribute);
}

$privileges = array_merge($privileges, $updatePrivileges, $selfUpdatePrivileges);

return $privileges;
