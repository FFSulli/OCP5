<?php

namespace App\Controller\Frontoffice;

use App\Model\Entity\ContactMessageModel;
use App\Model\Entity\Post;
use App\Model\Repository\PostRepository;
use App\Service\Http\Response;
use App\View\View;
use http\Client\Request;

class HomeController
{

    private View $view;
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository, View $view)
    {
        $this->postRepository = $postRepository;
        $this->view = $view;
    }

    public function displayHomepageAction(): Response
    {
        $posts = $this->postRepository->findBy([
            "post_status_fk" => 2
        ], null, 3, 0);

        return new Response($this->view->render([
            'template' => 'home',
            'data' => ['posts' => $posts],
        ]));
    }

    public function handleContactFormAction(Request $request)
    {
        if ($request->getRequestMethod() === 'POST') {
            $contactMessageModel = new ContactMessageModel();

            $firstName = $request->getBody()['firstName'];

            var_dump($firstName);
            return 'Handled submitted data';
        }


        return new Response($this->view->render([
            'template' => 'home'
        ]));

    }
}
