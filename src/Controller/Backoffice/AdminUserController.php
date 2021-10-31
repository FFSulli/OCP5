<?php

namespace App\Controller\Backoffice;

use App\Model\Repository\UserRepository;
use App\Service\Authentication\Authentication;
use App\Service\Http\RedirectResponse;
use App\Service\Http\Response;
use App\View\View;

class AdminUserController
{

    private View $view;
    private UserRepository $userRepository;
    private Authentication $authentication;

    public function __construct(View $view, UserRepository $userRepository, Authentication $authentication)
    {
        $this->view = $view;
        $this->userRepository = $userRepository;
        $this->authentication = $authentication;
    }

    public function displayAdminUserAction(): Response
    {
        if ($this->authentication->isAdmin() || $this->authentication->isEditor()) {
            $users = $this->userRepository->findAll();

            return new Response($this->view->render([
                'template' => '../backoffice/users',
                'data' => [
                    'users' => $users
                ]
            ]));
        }

        return new RedirectResponse('index.php', 403);
    }
}
