<?php

require __DIR__ . '/../../../../vendor/autoload.php';

$privleges = require __DIR__ . '/privileges.php';

$adminPrivileges = [];
foreach ($privleges as $privlege) {
    $adminPrivileges[$privlege] = true;
}

return $adminPrivileges;
