<?php

namespace App\Decorator;


use App\Enums\Like;
use App\Exceptions\ArgumentException;
use App\Exceptions\CommandException;
use JetBrains\PhpStorm\Pure;

class LikeDecorator extends Decorator implements DecoratorInterface

{
    public ?string $id;
    public ?string $author_id;
    public ?string $article_id;

    /**
     * @throws ArgumentException
     * @throws CommandException
     */
    public function __construct(array $arguments)
    {
        parent::__construct($arguments);

        $commentFieldData = $this->getFieldData();

        $this->id = $commentFieldData->get(Like::ID->value) ?? null;
        $this->author_id = $commentFieldData->get(Like::AUTHOR_ID->value) ?? null;
        $this->article_id = $commentFieldData->get(Like::ARTICLE_ID->value) ?? null;
    }

    #[Pure] public function getRequiredFields(): array
    {
        return Like::getRequiredFields();
    }
}