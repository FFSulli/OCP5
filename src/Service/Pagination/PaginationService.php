<?php

declare(strict_types=1);

namespace App\Service\Pagination;

use App\Model\Repository\PostRepository;
use App\Service\Database\MySQLDB;
use PDO;

class PaginationService
{
    private MySQLDB $database;
    private int $postsPerPage;

    public function __construct(MySQLDB $database, int $postsPerPage)
    {

        $this->database = $database;

        $this->postsPerPage = $postsPerPage;
    }

    /**
     * @return int
     */
    public function getPostsPerPage(): int
    {
        return $this->postsPerPage;
    }

    /**
     * @param int $postsPerPage
     * @return PaginationService
     */
    public function setPostsPerPage(int $postsPerPage): PaginationService
    {
        $this->postsPerPage = $postsPerPage;
        return $this;
    }

    public function countPosts(): int
    {
        $prepared = $this->database->prepare("SELECT COUNT(id) AS postsCount FROM posts");
        $prepared->execute();
        $result = $prepared->fetch();
        return (int) $result['postsCount'];
    }

    public function countPages(): int
    {
        $postsCount = $this->countPosts();
        $postsPerPage = $this->getPostsPerPage();

        return (int) ceil($postsCount / $postsPerPage);
    }

    public function displayPages(): array
    {
        $pages = [];
        $pagesCount = $this->countPages();

        for ($i = 1; $i <= $pagesCount; $i++) {
            $pages[$i] = $i;
        }

        return $pages;
    }


    public function paginatePosts(): array
    {

        $postsPerPage = $this->getPostsPerPage();

        if (isset($_GET['page'])) {
            $currentPage = $_GET['page'];
        } else {
            $currentPage = 1;
        }

        $start = ($currentPage - 1) * $postsPerPage;

        $prepared = $this->database->prepare('SELECT * FROM posts ORDER BY created_at DESC LIMIT :start, :postsPerPage');
        $prepared->bindValue(':start', $start, PDO::PARAM_INT);
        $prepared->bindValue(':postsPerPage', $postsPerPage, PDO::PARAM_INT);
        $prepared->execute();
        return $prepared->fetchAll();
    }

}
