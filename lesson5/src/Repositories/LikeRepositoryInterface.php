<?php

namespace App\Repositories;

use App\Entities\Like\Like;
use PDOStatement;

interface LikeRepositoryInterface
{
public function get(int $id):Like;
public function getLike(PDOStatement $statement, int $id):Like;
}