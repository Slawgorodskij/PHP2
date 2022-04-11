<?php

namespace App\Connections;


use App\config\SqlLiteConfig;

class SqlLiteConnector extends Connector implements SqlLiteConnectorInterface
{
    public function getDsn(): string
    {
        return SqlLiteConfig::DSN;
    }
}