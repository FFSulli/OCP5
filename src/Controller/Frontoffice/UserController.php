<?php

declare(strict_types=1);

namespace  App\Controller\Frontoffice;

use App\Model\Entity\User;
use App\Model\Repository\PostRepository;
use App\Service\Authentication\Authentication;
use App\Service\CSRF\Csrf;
use App\Service\Email\EmailService;
use App\Service\Form\LoginFormValidator;
use App\Service\Form\RegisterFormValidator;
use App\Service\Http\RedirectResponse;
use App\View\View;
use App\Service\Http\Request;
use App\Service\Http\Response;
use App\Service\Http\Session\Session;
use App\Model\Repository\UserRepository;
use Twig\Environment;

final class UserController
{
    private UserRepository $userRepository;
    private Authentication $authentication;
    private View $view;
    private Session $session;
    private Csrf $csrf;


    public function __construct(UserRepository $userRepository, Authentication $authentication, View $view, Session $session, Csrf $csrf)
    {
        $this->userRepository = $userRepository;
        $this->authentication = $authentication;
        $this->view = $view;
        $this->session = $session;
        $this->csrf = $csrf;
    }

    public function loginAction(Request $request, LoginFormValidator $loginFormValidator)
    {

        $data = $request->request()->all();

        if ($this->authentication->isAuthenticated()) {
            return new RedirectResponse('index.php?action=home', 302);
        }

        if ($request->getMethod() === 'POST') {
            if ($loginFormValidator->isValid($data) && $this->csrf->checkToken($data['csrfToken'])) {
                $this->session->addFlashes('success', 'Vous êtes désormais connecté.');
                $this->authentication->authenticate($data['email']);
                return new RedirectResponse('/', 200);
            } else {
                $this->session->addFlashes('error', "Le formulaire n'est pas valide, merci de vérifier les informations renseignées.");
            }
        }

        $this->csrf->generateToken();

        return new Response($this->view->render([
            'template' => 'login',
            'data' => [
                'csrfToken' => $this->session->get('csrfToken')
            ]
        ]));
    }

    public function logoutAction(): Response
    {
        if ($this->authentication->isAuthenticated()) {
            $this->session->addFlashes('success', 'Vous avez été correctement déconnecté.');
            $this->authentication->logout();
            return new RedirectResponse('/', 302);
        }

        return new RedirectResponse('/', 403);
    }

    public function registerAction(Request $request, RegisterFormValidator $registerFormValidator, EmailService $emailService): Response
    {

        if ($this->authentication->isAuthenticated()) {
            return new RedirectResponse('/', 302);
        }

        $data = $request->request()->all();

        if ($request->getMethod() === 'POST') {

            if ($registerFormValidator->isValid($data) && $this->csrf->checkToken($data['csrfToken'])) {
                $password = password_hash($data['password'], PASSWORD_BCRYPT);
                $user = new User();
                $user
                    ->setFirstName($data['firstName'])
                    ->setLastName($data['lastName'])
                    ->setEmail($data['email'])
                    ->setPassword((string) $password);

                $this->userRepository->create($user);

                $mailer = $emailService->prepareEmail();
                $message = $emailService->createMessage('Sullivan Berger - Nouveau compte' ,$data['email'], 'Merci de vous êtes inscrit sur sullivan-berger.fr.');
                $mailer->send($message);
                $this->session->addFlashes('success', 'Vous êtes désormais inscrit, bienvenue !');

                return new RedirectResponse('/', 302);
            } else {
                $oldRequest = $request->request()->all();
                $this->session->addFlashes('error', "Le formulaire n'est pas valide, merci de vérifier les informations renseignées.");
            }

        }

        $this->csrf->generateToken();

        return new Response($this->view->render([
            'template' => 'register',
            'data' => [
                'request' => $oldRequest,
                'csrfToken' => $this->session->get('csrfToken')
            ]
        ]));
    }
}
