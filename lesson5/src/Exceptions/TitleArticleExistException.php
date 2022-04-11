<?php

namespace App\Exceptions;

class TitleArticleExistException extends \Exception
{
    protected $message = 'Такая статья уже написана и размещена';
}