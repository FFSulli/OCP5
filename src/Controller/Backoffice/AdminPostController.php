<?php


namespace App\Controller\Backoffice;

use App\Service\Http\Response;
use App\View\View;

class AdminPostController
{
    /**
     * @var View
     */
    private View $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function displayAdminPostAction(): Response
    {
        return new Response($this->view->render([
            'template' => '../backoffice/posts',
        ]));
    }

}
