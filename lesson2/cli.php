<?php

use App\Enums\Argument;
use App\Exceptions\UserNotFoundException;
use App\Factories\EntityFactory;
use App\Factories\RepositoryFactory;

$user = EntityFactory::getInstance()->create(Argument::USER->value);

$factory = new RepositoryFactory();
$entityRepository = $factory->create($user);

$entityRepository->save($user);

try {
    $entityRepository->get(12312312312312);
}catch (UserNotFoundException $e)
{
    echo $e->getMessage();
}




