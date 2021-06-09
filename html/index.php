<?php

try {
    $client = new Redis();
    $client->connect('redis', '6379');
} catch (Exception $exception) {
    var_dump('<br>Redis connection problem: '.$exception->getMessage());
    exit;
}

try {
    $client->setex('test:key', 3600, "Hello from Redis");
} catch (Exception $exception) {
    var_dump('<br>Redis store problem: '.$exception->getMessage());
    exit;
}

var_dump('<br>Redis has a value of: '.$client->get('test:key'));

$client->close();

$dotEnv = readMyDotEnvFile('.env');
if (! $dotEnv) {
    var_dump('<br>Dot env file is missing');
    exit;
}

try {
    $db_local = [
        'host' => 'mysql',
        'dbname' => readDotEnv($dotEnv, 'DB_NAME'),
        'user' => readDotEnv($dotEnv, 'DB_USER'),
        'pass' => readDotEnv($dotEnv, 'DB_PASS'),
        'port' => 3306
    ];
} catch (Exception $exception) {
    var_dump('<br>It seems that are parameters missing from the .ENV file, the message is: '.$exception->getMessage());
    exit;
}


$stringLocal = 'mysql:host='.$db_local['host'].';dbname='.$db_local['dbname']
    .';port='.$db_local['port'].'; charset=utf8';

try {
    $pdo = new PDO($stringLocal, $db_local['user'], $db_local['pass']);
} catch (Exception $e) {
    var_dump('<br>Bad MySQL: ' . $e->getMessage()); exit;
}

var_dump('<br>seems to be working');


function readMyDotEnvFile($path) {
    try
    {
        if ($f = file_get_contents($path)) {
            return explode(PHP_EOL, $f);
        }
    } catch (Exception $exception) {
        var_dump('<br>File not found, more info: '.$exception->getMessage());
    }

    return false;
}

function readDotEnv($dotEnvData, $key) {
    foreach ($dotEnvData as $dotEnvParam) {
        $dotEnvParamInfo = explode('=', $dotEnvParam);
        if ($dotEnvParamInfo[0] === $key) {
            return substr($dotEnvParamInfo[1], 1, strlen($dotEnvParamInfo[1])-2);
        }
    }
    return '<br>Key: '.$key.' not found';
}