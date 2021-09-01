<?php

declare(strict_types=1);

namespace  App\Controller\Frontoffice;

use App\Model\Entity\User;
use App\Service\Form\LoginFormValidator;
use App\Service\Form\RegisterFormValidator;
use App\View\View;
use App\Service\Http\Request;
use App\Service\Http\Response;
use App\Service\Http\Session\Session;
use App\Model\Repository\UserRepository;

final class UserController
{
    private UserRepository $userRepository;
    private View $view;
    private Session $session;


    public function __construct(UserRepository $userRepository, View $view, Session $session)
    {
        $this->userRepository = $userRepository;
        $this->view = $view;
        $this->session = $session;
    }

    public function loginAction(Request $request, LoginFormValidator $loginFormValidator): Response
    {
        $data = $request->request()->all();

        if ($request->getMethod() === 'POST') {
            if ($loginFormValidator->isValid($data)) {
                $this->session->addFlashes('success', 'Vous êtes désormais connecté.');
                $this->session->set('user', $data['email']);
                return new Response($this->view->render(['template' => 'home']));
            } else {
                $this->session->addFlashes('error', "Le formulaire n'est pas valide, merci de vérifier les informations renseignées.");
            }
        }
        return new Response($this->view->render(['template' => 'login', 'data' => []]));
    }

    public function logoutAction(): Response
    {
        $this->session->remove('user');
        return new Response($this->view->render(['template' => 'home']));
    }

    public function registerAction(Request $request, RegisterFormValidator $registerFormValidator): Response
    {

        $data = $request->request()->all();

        if ($request->getMethod() === 'POST') {
            if ($registerFormValidator->isValid($data)) {
                $password = password_hash($data['password'], PASSWORD_BCRYPT);
                $user = new User();
                $user
                    ->setFirstName($data['firstName'])
                    ->setLastName($data['lastName'])
                    ->setEmail($data['email'])
                    ->setPassword((string) $password);

                $this->userRepository->create($user);
                $this->session->addFlashes('success', 'Vous êtes désormais inscrit, bienvenue !');

                return new Response($this->view->render(['template' => 'home']));
            }

            $this->session->addFlashes('error', "Le formulaire n'est pas valide, merci de vérifier les informations renseignées.");
        }

        return new Response($this->view->render(['template' => 'register']));
    }
}
