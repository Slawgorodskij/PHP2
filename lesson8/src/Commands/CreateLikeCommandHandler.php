<?php

namespace App\Commands;

use App\Drivers\Connection;
use App\Entities\Like\Like;
use App\Entities\Like\LikeInterface;
use App\Repositories\LikeRepositoryInterface;
use Psr\Log\LoggerInterface;

class CreateLikeCommandHandler implements CommandHandlerInterface
{

    public function __construct(
        private LikeRepositoryInterface $likeRepository,
        private Connection              $connection,
        private LoggerInterface         $logger,
    )
    {
    }

    /**
     * @var CreateEntityCommand $command
     */
    public function handle(CommandInterface $command): LikeInterface
    {
        $this->logger->info('Create Like command started');
        /**
         * @var Like $like
         */
        $like = $command->getEntity();
        $this->connection->prepare($this->getSQL())->execute(
            [
                ':author_id' => $like->getAuthor()->getId(),
                ':article_id' => $like->getArticle()->getId(),
            ]
        );
        $id = $this->connection->lastInsertId();
        $this->logger->info('Like created');

        return $this->likeRepository->findById($id);
    }

    public function getSQL(): string
    {
        return "INSERT INTO Like(author_id, article_id) 
        VALUES (:author_id, :article_id)";
    }
}