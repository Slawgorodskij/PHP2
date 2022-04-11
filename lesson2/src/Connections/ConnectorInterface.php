<?php

namespace App\Connections;

use PDO;

interface ConnectorInterface
{
    public function getConnection(
        $dsn,
        $username = null,
        $password = null,
        $options = null
    ):PDO;
}