<?php

namespace App\Controller\Backoffice;

use App\Model\Repository\UserRepository;
use App\Service\Authentication\Authentication;
use App\Service\CSRF\Csrf;
use App\Service\Http\RedirectResponse;
use App\Service\Http\Request;
use App\Service\Http\Response;
use App\Service\Http\Session\Session;
use App\View\View;

class AdminUserController
{

    private View $view;
    private UserRepository $userRepository;
    private Authentication $authentication;
    private Request $request;
    private Csrf $csrf;
    private Session $session;

    public function __construct(View $view, UserRepository $userRepository, Authentication $authentication, Request $request, Csrf $csrf, Session $session)
    {
        $this->view = $view;
        $this->userRepository = $userRepository;
        $this->authentication = $authentication;
        $this->request = $request;
        $this->csrf = $csrf;
        $this->session = $session;
    }

    public function displayAdminUserAction(): Response
    {
        if ($this->authentication->isAdmin() || $this->authentication->isEditor()) {
            $users = $this->userRepository->findAll();

            $data = $this->request->request()->all();

            if ($this->request->getMethod() === 'POST') {
                if ($this->authentication->isAdmin()) {
                    if ($data['readerRole']) {

                        if ($this->userRepository->find($data['readerRole']) !== null && $this->csrf->checkToken($data['csrfToken'])) {
                            $user = $this->userRepository->find((int) $data['readerRole']);

                            $user->setRoleFk(1);
                            $this->userRepository->update($user);

                            $this->session->addFlashes('success', 'L\'utilisateur a désormais le rôle lecteur');
                            return new RedirectResponse('/admin/users', 302);
                        }
                    }
                    if ($data['editorRole']) {
                        if ($this->userRepository->find($data['editorRole']) !== null && $this->csrf->checkToken($data['csrfToken'])) {
                            $user = $this->userRepository->find((int) $data['editorRole']);

                            $user->setRoleFk(2);
                            $this->userRepository->update($user);

                            $this->session->addFlashes('success', 'L\'utilisateur a désormais le rôle éditeur');
                            return new RedirectResponse('/admin/users', 302);
                        }
                    }
                    if ($data['adminRole']) {
                        if ($this->userRepository->find($data['adminRole']) !== null && $this->csrf->checkToken($data['csrfToken'])) {
                            $user = $this->userRepository->find((int) $data['adminRole']);

                            $user->setRoleFk(3);
                            $this->userRepository->update($user);

                            $this->session->addFlashes('success', 'L\'utilisateur a désormais le rôle admin');
                            return new RedirectResponse('/admin/users', 302);
                        }
                    }
                    if ($data['deleteUser']) {
                        if ($this->userRepository->find($data['deleteUser']) !== null && $this->csrf->checkToken($data['csrfToken'])) {
                            $user = $this->userRepository->find((int) $data['deleteUser']);

                            $this->userRepository->delete($user);

                            $this->session->addFlashes('success', 'L\'utilisateur a été supprimé');
                            return new RedirectResponse('/admin/users', 302);
                        }
                    }
                }
            }

            $this->csrf->generateToken();

            return new Response($this->view->render([
                'template' => '../backoffice/users',
                'data' => [
                    'users' => $users,
                    'csrfToken' => $this->session->get('csrfToken')
                ]
            ]));
        }

        return new RedirectResponse('index.php', 403);
    }
}
