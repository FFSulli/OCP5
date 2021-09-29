<?php

namespace App\Controller\Backoffice;

use App\Model\Repository\UserRepository;
use App\Service\Http\Response;
use App\View\View;

class AdminUserController
{
    /**
     * @var View
     */
    private View $view;
    private UserRepository $userRepository;

    public function __construct(View $view, UserRepository $userRepository)
    {
        $this->view = $view;
        $this->userRepository = $userRepository;
    }

    public function displayAdminUserAction(): Response
    {
        $users = $this->userRepository->findAll();

        return new Response($this->view->render([
            'template' => '../backoffice/users',
            'data' => [
                'users' => $users
            ]
        ]));
    }
}
