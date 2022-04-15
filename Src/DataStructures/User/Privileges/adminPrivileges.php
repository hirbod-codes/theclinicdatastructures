<?php

$privileges = include __DIR__ . '/privileges.php';

$adminPrivileges = [];
foreach ($privileges as $privlege) {
    $adminPrivileges[$privlege] = true;
}

return $adminPrivileges;
