<?php

namespace App\Models;

class User
{
    public function __construct(
        protected int    $id,
        protected string $firstname,
        protected string $lastname,
    )
    {
    }

    public function __toString()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

}