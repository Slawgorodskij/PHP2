<?php

namespace Repositories;

use App\config\SqlLiteConfig;
use App\Connections\ConnectorInterface;
use App\Drivers\Connection;
use App\Drivers\PdoConnectionDriver;
use App\Exceptions\CommentNotFoundException;
use App\Repositories\CommentRepository;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class CommentRepositoryTest extends TestCase
{
    public function testItThrowsAnExceptionWhenCommentNotFound(): void
    {
        $statementStub = $this->createStub(PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);

        $repository = new CommentRepository($this->getConnectionStub());

        $this->expectException(CommentNotFoundException::class);
        $this->expectExceptionMessage('Comment not found');

        $repository->get('1');
    }

    private function getConnectionStub(): ConnectorInterface
    {
        return new class implements ConnectorInterface {

            public function getConnection(): Connection
            {
                return PdoConnectionDriver::getInstance(SqlLiteConfig::DSN);
            }
        };
    }
}