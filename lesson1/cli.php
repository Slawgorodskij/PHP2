<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Comment;
use App\Models\News;
use App\Models\User;

$faker = Faker\Factory::create();

switch ($argv[1]) {
    case 'user':
        $user = new User(1, $faker->firstName, $faker->lastName);
        echo $user->__toString();
        break;
    case 'news':
        $news = new News(1, 1, $faker->title, $faker->text);
        echo $news->__toString();
        break;
    case 'comment';
        $comment = new Comment(1, 1, 1, $faker->text);
        echo $comment->__toString();
        break;
}