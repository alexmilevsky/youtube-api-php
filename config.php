<?php

$DEVELOPER_KEY = 'AIzaSyCAKghrx-iQarm9RfDWOH8ssToUzuiSyTo';

if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    throw new \Exception('please run "composer require google/apiclient:~2.0" in "' . __DIR__ .'"');
}
require_once __DIR__ . '/vendor/autoload.php';
?>