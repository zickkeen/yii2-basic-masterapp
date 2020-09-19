<?php

$config = require_once __DIR__ . '/config.php';

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host='.$config['mysql']['host'].';dbname='.$config['mysql']['name'],
    'username' => $config['mysql']['user'],
    'password' => $config['mysql']['pass'],
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
