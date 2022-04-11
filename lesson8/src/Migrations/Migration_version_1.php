<?php

namespace App\Migrations;

use App\Connections\ConnectorInterface;
use App\Connections\SqlLiteConnector;
use JetBrains\PhpStorm\Pure;

class Migration_version_1 implements Migrations
{
    private ConnectorInterface $connector;

    #[Pure] public function __construct(ConnectorInterface $connector = null)
    {
        $this->connector = $connector ?? new SqlLiteConnector();
    }

    public function execute(): void
    {
        $this->connector->getConnection()->query(
            "create table User
                        (
                            id integer
                            constraint users_pk primary key autoincrement,
                            first_name varchar,
                            last_name  varchar,
                            email      varchar
                        );            
                        create unique index users_email_uindex on User (email);
                     "
        );

        $this->connector->getConnection()->query(
            "
                    create table Article
                    (
                        id integer primary key autoincrement,
                        author_id integer,
                        title     varchar,
                        text      varchar,
                        FOREIGN KEY(author_id) REFERENCES User(id)
                    );
                     "
        );

        $this->connector->getConnection()->query(
            "
                 create table Comment
                (
                    id  integer primary key autoincrement,
                    author_id integer,
                    article_id integer,
                    text  varchar,
                    FOREIGN KEY(author_id) REFERENCES User(id),
                    FOREIGN KEY(article_id) REFERENCES Article(id)
                );
                     "
        );

        $this->connector->getConnection()->query(
            "
                 create table Like
                (
                    id  integer primary key autoincrement,
                    author_id integer,
                    article_id integer,
                    FOREIGN KEY(author_id) REFERENCES User(id),
                    FOREIGN KEY(article_id) REFERENCES Article(id)
                );
                     "
        );

        $this->connector->getConnection()->query(
            "
                 create table Token
                (
                    token text NOT NULL CONSTRAINT 
                        token_primary_key PRIMARY KEY,
                    author_id integer NOT NULL,
                    expires_on text NOT NULL,
                    FOREIGN KEY(author_id) REFERENCES User(id)
                );
                     "
        );
    }
}