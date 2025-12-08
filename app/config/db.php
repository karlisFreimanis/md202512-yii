<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => sprintf(
        'sqlsrv:Server=%s,%s;Database=%s;Encrypt=no;TrustServerCertificate=yes',
        getenv('MSSQL_DB_HOST') ?: 'yii2-mssql',
        getenv('MSSQL_DB_PORT') ?: '1433',
        getenv('MSSQL_DB_NAME') ?: 'master'
    ),
    'username' => getenv('MSSQL_DB_USER') ?: 'sa',
    'password' => getenv('MSSQL_SA_PASSWORD') ?: 'YourStrong@Passw0rd',
    'charset' => 'utf8',
];

