<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Service\Database\MySQLDB;
use App\Model\Entity\Post;
use Config\DotEnv;

(new DotEnv(__DIR__ . '/../.env'))->load();

$db = new MySQLDB(getenv('DATABASE_HOST'), getenv('DATABASE_NAME'), getenv('DATABASE_USER'), getenv('DATABASE_PASSWORD'));

$prepared = $db->prepare('select * from posts where id = :id');
$post = $db->execute($prepared, [
    ":id" => 2
], Post::class);

var_dump($prepared);

