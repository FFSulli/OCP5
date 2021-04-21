<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Service\Database\MySQLDB;
use Config\DotEnv;

(new DotEnv(__DIR__ . '/../.env'))->load();

$db = new MySQLDB(getenv('DATABASE_HOST'), getenv('DATABASE_NAME'), getenv('DATABASE_USER'), getenv('DATABASE_PASSWORD'));

$id = 1;
$prepared = $db->prepare("SELECT * FROM posts");
$result = $db->execute($prepared);

var_dump($result);
