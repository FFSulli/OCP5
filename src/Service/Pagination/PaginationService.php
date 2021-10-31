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
    private PostRepository $postRepository;

    public function __construct(MySQLDB $database, PostRepository $postRepository, int $postsPerPage)
    {

        $this->database = $database;
        $this->postsPerPage = $postsPerPage;
        $this->postRepository = $postRepository;

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


    public function countPages(): int
    {
        $postsCount = $this->postRepository->countPosts([]);

        if ($this->postsPerPage <= 0) {
            return 0;
        }

        return (int) ceil($postsCount / $this->postsPerPage);

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

        if (isset($_GET['page'])) {
            $currentPage = $_GET['page'];
        } else {
            $currentPage = 1;
        }

        $start = ($currentPage - 1) * $this->postsPerPage;

        return $this->postRepository->findBy([], ["created_at" => "DESC"], $this->postsPerPage, $start);

    }

}
