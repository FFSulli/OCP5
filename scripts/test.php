<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Service\Database\MySQLDB;
use App\Model\Entity\Post;
use Config\DotEnv;

(new DotEnv(__DIR__ . '/../.env'))->load();

//$db = new MySQLDB(getenv('DATABASE_HOST'), getenv('DATABASE_NAME'), getenv('DATABASE_USER'), getenv('DATABASE_PASSWORD'));
//
//$prepared = $db->prepare('select * from posts where user_fk = 5 AND created_at = "2021-02-17 08:53:34" ORDER BY id DESC');
//$post = $db->execute($prepared, [], Post::class);
//
//var_dump($post);

$newRouter = new \App\Service\NewRouter(require __DIR__ . "/../config/routes.php");

var_dump($newRouter);

