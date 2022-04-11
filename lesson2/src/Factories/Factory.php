<?php

namespace App\Factories;

use App\Entities\EntityInterface;

use \Faker\Factory as FakerFactory;
use Faker\Generator;

abstract class Factory implements FactoryInterface
{
    protected ?Generator $faker = null;

    public function __construct(?FakerFactory $faker = null) {
        $this->faker = $facker ?? FakerFactory::create();
    }

    abstract public function create():EntityInterface;
}