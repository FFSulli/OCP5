<?php

namespace App\Controller\Backoffice;

use App\Model\Repository\PostRepository;
use App\Service\Http\Response;
use App\View\View;

class AdminPostController
{

    private View $view;
    private PostRepository $postRepository;

    public function __construct(View $view, PostRepository $postRepository)
    {
        $this->view = $view;
        $this->postRepository = $postRepository;
    }

    public function displayAdminPostAction(): Response
    {

        $posts = $this->postRepository->findAll();
        return new Response($this->view->render([
            'template' => '../backoffice/posts',
            'data' => [
                'posts' => $posts
            ]
        ]));
    }
}
