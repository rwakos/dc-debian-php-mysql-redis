<?php

// phpinfo();
try {
    $client = new Redis();
    $client->connect('redis', '6379');
} catch (Exception $exception) {
    $client = false;
    var_dump('Redis connection problem: '.$exception->getMessage());
}

var_dump($client->get('test:key'));

$client->close();

$db_local = [
    'host' => 'mysql',
    'dbname' => 'test',
    'user' => 'api',
    'pass' => 'SomePass123....)(*&WWW',
    'port' => 3306
];

$stringLocal = 'mysql:host='.$db_local['host'].';dbname='.$db_local['dbname']
    .';port='.$db_local['port'].'; charset=utf8';

try {
    $pdo = new PDO($stringLocal, $db_local['user'], $db_local['pass']);
} catch (Exception $e) {
    var_dump('Bad MySQL: ' . $e->getMessage()); exit;
}

var_dump('seems to be working');