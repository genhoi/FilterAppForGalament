<?php
/**
 * Created by PhpStorm.
 * User: genho
 * Date: 09.04.2016
 * Time: 23:52
 */

require __DIR__ . '/../vendor/autoload.php';

$action = $argv[1];

$commandController = new genhoi\Controller\ConsoleController(new \Predis\Client());

if (method_exists($commandController, $action)) {

    $param = $argv[2];

    if ($param) {
        $commandController->{$action}($param);
    } else {
        $commandController->{$action}();
    }
} else {
    echo 'Action doesn\'t exist';
}

