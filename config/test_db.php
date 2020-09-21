<?php
$cfg = require __DIR__ . '/config.php';

return [
    'class' => 'yii\db\Connection',
    'dsn' => $cfg['db']['type'].':host='.$cfg['db']['host'].';dbname='.$cfg['db']['test'],
    'username' => $cfg['db']['user'],
    'password' => $cfg['db']['pass'],
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];