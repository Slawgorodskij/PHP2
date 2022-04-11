<?php

namespace App\Http\Actions\Like;


use App\Commands\CreateLikeCommandHandler;
use App\Commands\EntityCommand;
use App\Entities\Like\Like;
use App\Exceptions\HttpException;
use App\Http\Actions\User\ActionInterface;
use App\Http\Auth\AuthenticationInterface;
use App\Http\AuthArticles\IdentificationArticleInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use Psr\Log\LoggerInterface;

class CreateLike implements ActionInterface
{
    public function __construct(
        private CreateLikeCommandHandler       $createLikeCommandHandler,
        private AuthenticationInterface        $authentication,
        private IdentificationArticleInterface $identificationArticle,
        private LoggerInterface                $logger,
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $like = new Like(
                $this->authentication->getUser($request),
                $this->identificationArticle->getArticle($request),
            );
            $like = $this->createLikeCommandHandler->handle(new EntityCommand($like));
        } catch (HttpException $e) {
            $this->logger->error($e);
            return new ErrorResponse($e->getMessage());
        }

        $data = [
            'id' => $like->getId(),
            'author_id' => $like->getAuthor()->getId(),
            'article_id' => $like->getArticle()->getId(),
        ];

        $this->logger->info('Create new Like', $data);

        return new SuccessfulResponse([
            'like' => "Поставлен like",
        ]);
    }
}