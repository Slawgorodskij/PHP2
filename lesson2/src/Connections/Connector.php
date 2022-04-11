<?php

namespace App\Connections;

use PDO;

abstract class Connector implements ConnectorInterface
{
    public function getConnection($dsn, $username = null, $password = null, $options = null):PDO
    {
        return new PDO($dsn, $username, $password, $options);
    }

    abstract public function getDsn():string;
}