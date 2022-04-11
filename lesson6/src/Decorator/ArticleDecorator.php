<?php

namespace App\Decorator;

use App\Enums\Article;
use App\Exceptions\ArgumentException;
use App\Exceptions\CommandException;
use JetBrains\PhpStorm\Pure;

class ArticleDecorator extends Decorator implements DecoratorInterface
{
    public ?string $id;
    public ?string $author_id;
    public ?string $title;
    public ?string $text;

    /**
     * @throws ArgumentException
     * @throws CommandException
     */
    public function __construct(array $arguments)
    {
        parent::__construct($arguments);

        $articleFieldData = $this->getFieldData();

        $this->id = $articleFieldData->get(Article::ID->value) ?? null;
        $this->author_id = $articleFieldData->get(Article::AUTHOR_ID->value) ?? null;
        $this->title = $articleFieldData->get(Article::TITLE->value) ?? null;
        $this->text = $articleFieldData->get(Article::TEXT->value) ?? null;
    }


    #[Pure] public function getRequiredFields(): array
    {
       return Article::getRequiredFields();
    }
}