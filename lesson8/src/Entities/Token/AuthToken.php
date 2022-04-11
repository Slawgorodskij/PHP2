<?php

namespace App\Entities\Token;

use App\Entities\User\User;
use App\Traits\Author;
use DateTimeImmutable;
use DateTimeInterface;

class AuthToken
{
    public const TABLE_NAME = 'Token';

    use Author;

    public function __construct(
        string            $token,
        User              $author,
        DateTimeImmutable $expiresOn
    )
    {
        $this->token = $token;
        $this->author = $author;
        $this->expiresOn = $expiresOn;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getExpiresOn(): DateTimeImmutable
    {
        return $this->expiresOn;
    }

    public function isExpires(): bool
    {
        return new \DateTimeImmutable() > $this->getExpiresOn();
    }

    public function __toString(): string
    {
        return sprintf(
            "[%d] %s %s",
            $this->getToken(),
            $this->getAuthor()->getId(),
            $this->getExpiresOnString()
        );
    }

    public function __serialize(): array
    {
        return
            [
                'token' => $this->getToken(),
                'userId' => $this->getAuthor()->getId(),
                'expiresOn' => $this->getExpiresOnString()
            ];
    }

    private function getExpiresOnString(): string
    {
        return $this->getExpiresOn()->format(DateTimeInterface::ATOM);
    }

    public function getTableName(): string
    {
        return static::TABLE_NAME;
    }
}