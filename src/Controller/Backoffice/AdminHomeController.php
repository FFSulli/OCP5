<?php

namespace App\Controller\Backoffice;

use App\Service\Authentication\Authentication;
use App\Service\Http\RedirectResponse;
use App\Service\Http\Response;
use App\View\View;

class AdminHomeController
{

    private View $view;
    private Authentication $authentication;

    public function __construct(View $view, Authentication $authentication)
    {
        $this->view = $view;
        $this->authentication = $authentication;
    }

    public function displayAdminHomepageAction(): Response
    {

        if ($this->authentication->isAdmin() || $this->authentication->isEditor()) {
            return new Response($this->view->render([
                'template' => '../backoffice/home',
            ]));
        }

        return new RedirectResponse('index.php?action=home', 200);
    }
}
