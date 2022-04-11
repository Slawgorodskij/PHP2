<?php

namespace App\Decorator;

use App\Enums\Comment;
use App\Exceptions\ArgumentException;
use App\Exceptions\CommandException;
use JetBrains\PhpStorm\Pure;

class CommentDecorator extends Decorator implements DecoratorInterface
{
    public ?string $id;
    public ?string $author_id;
    public ?string $article_id;
    public ?string $text;

    /**
     * @throws ArgumentException
     * @throws CommandException
     */
    public function __construct(array $arguments)
    {
        parent::__construct($arguments);

        $commentFieldData = $this->getFieldData();

        $this->id = $commentFieldData->get(Comment::ID->value) ?? null;
        $this->author_id = $commentFieldData->get(Comment::AUTHOR_ID->value) ?? null;
        $this->article_id = $commentFieldData->get(Comment::ARTICLE_ID->value) ?? null;
        $this->text = $commentFieldData->get(Comment::TEXT->value) ?? null;
    }


    #[Pure] public function getRequiredFields(): array
    {
        return Comment::getRequiredFields();
    }
}