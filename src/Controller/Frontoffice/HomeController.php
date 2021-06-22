<?php

namespace App\Controller\Frontoffice;

use App\Model\Repository\PostRepository;
use App\Service\Http\Response;
use App\View\View;

class HomeController
{

    private View $view;
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository, View $view)
    {
        $this->postRepository = $postRepository;
        $this->view = $view;
    }

    public function index(): Response
    {
        $posts = $this->postRepository->findBy([
            "post_status_fk" => 2
        ], null, 3, 0);

        return new Response($this->view->render([
            'template' => 'home',
            'data' => ['posts' => $posts],
        ]));
    }
}
