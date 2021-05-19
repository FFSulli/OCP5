<?php


namespace App\Controller\Frontoffice;


use App\Service\Http\Response;
use App\View\View;

class HomeController
{
    /**
     * @var View
     */
    private View $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function index(): Response
    {
        return new Response($this->view->render([
            'template' => 'home',
        ]));
    }
}
