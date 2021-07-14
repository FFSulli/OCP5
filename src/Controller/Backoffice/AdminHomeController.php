<?php

namespace App\Controller\Backoffice;

use App\Service\Http\Response;
use App\View\View;

class AdminHomeController
{
    /**
     * @var View
     */
    private View $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function displayAdminHomepageAction(): Response
    {
        return new Response($this->view->render([
            'template' => '../backoffice/home',
        ]));
    }
}
