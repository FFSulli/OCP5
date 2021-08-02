<?php

declare(strict_types=1);

namespace  App\Controller\Frontoffice;

use App\Model\Entity\User;
use App\Service\Form\RegisterFormValidator;
use App\View\View;
use App\Service\Http\Request;
use App\Service\Http\Response;
use App\Service\Http\Session\Session;
use App\Model\Repository\UserRepository;

final class UserController
{
    private UserRepository $userRepository;
    private RegisterFormValidator $registerFormValidator;
    private View $view;
    private Session $session;

    private function isValidLoginForm(?array $infoUser): bool
    {
        if ($infoUser === null) {
            return false;
        }

        $user = $this->userRepository->findOneBy(['email' => $infoUser['email']]);
        if ($user === null || $infoUser['password'] !== $user->getPassword()) {
             return false;
        }

        $this->session->set('user', $user);

        return true;
    }


    public function __construct(UserRepository $userRepository, RegisterFormValidator $registerFormValidator, View $view, Session $session)
    {
        $this->userRepository = $userRepository;
        $this->view = $view;
        $this->session = $session;
        $this->registerFormValidator = $registerFormValidator;
    }

    public function loginAction(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {
            if ($this->isValidLoginForm($request->request()->all())) {
                return new Response('<h1>Utilisateur connecté</h1><h2>faire une redirection vers la page d\'accueil</h2><a href="index.php?action=posts">Liste des posts</a><br>', 200);
            }
            $this->session->addFlashes('error', 'Mauvais identifiants');
        }
        return new Response($this->view->render(['template' => 'login', 'data' => []]));
    }

    public function logoutAction(): Response
    {
        $this->session->remove('user');
        return new Response('<h1>Utilisateur déconnecté</h1><h2>faire une redirection vers la page d\'accueil</h2><a href="index.php?action=posts">Liste des posts</a><br>', 200);
    }

    public function registerAction(Request $request): Response
    {

        $data = $request->request()->all();

        if ($request->getMethod() === 'POST') {
            if ($this->registerFormValidator->isValid($data)) {
                $password = password_hash($data['password'], PASSWORD_BCRYPT);
//                if ($password === false) {
//                    Renvoyer erreur
//                }
                $user = new User();
                $user
                    ->setFirstName($data['firstName'])
                    ->setLastName($data['lastName'])
                    ->setEmail($data['email'])
                    ->setPassword((string) $password);

                var_dump($user);

                $this->userRepository->create($user);

                return new Response('<h1>Formulaire envoyé</h1><h2>faire une redirection vers la page d\'accueil</h2><a href="index.php?action=posts">Page d\'accueil</a><br>', 200);
            }

            $this->session->addFlashes('error', 'Formulaire mal renseigné');
        }

        return new Response($this->view->render(['template' => 'register']));
    }
}
