<?php

namespace App\Factories;

use App\Entities\EntityInterface;
use App\Enums\Argument;
use App\Exceptions\MatchException;

class EntityFactory implements EntityFactoryInterface
{
    private static UserFactoryInterface $userFactory;
    private static ArticleFactoryInterface $articleFactory;
    private static CommentFactoryInterface $commentFactory;
    private static array $instances = [];

    private function __construct(
        UserFactoryInterface $userFactory = null,
        ArticleFactoryInterface $articleFactory = null,
        CommentFactoryInterface $commentFactory = null
    )
    {
        self::$userFactory = $userFactory ?? new UserFactory();
        self::$articleFactory = $articleFactory ?? new ArticleFactory(self::$userFactory);
        self::$commentFactory = $commentFactory ?? new CommentFactory(self::$userFactory, self::$articleFactory);
    }


    public static function getInstance(
        UserFactoryInterface $userFactory = null,
        ArticleFactoryInterface $articleFactory = null,
        CommentFactoryInterface $commentFactory = null
    ): EntityFactoryInterface
    {
        $class = static::class;//MyEntityFactory
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] =
                new static(
                    $userFactory,
                    $articleFactory,
                    $commentFactory
            );
        }

        return self::$instances[$class];
    }

    /**
     * @throws MatchException
     */
    public function create(string $type): EntityInterface
    {
        return match ($type)
        {
            Argument::USER->value => self::$userFactory->create(),
            Argument::ARTICLE->value => self::$articleFactory->create(),
            Argument::COMMENT->value => self::$commentFactory->create(),
            default => throw new MatchException(
                sprintf(
                    "Аргумент должен содержать одно из перечисленных значений: '%s'.",
                    implode("', '", array_map(fn (Argument $argument) => $argument->value, Argument::ARGUMENTS))
                )
            )
        };
    }
}