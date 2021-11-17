<?php

namespace App\Controller\Frontoffice;

use App\Model\Repository\PostRepository;
use App\Service\Authentication\Authentication;
use App\Service\CSRF\Csrf;
use App\Service\Email\EmailService;
use App\Service\Form\ContactFormValidator;
use App\Service\Http\RedirectResponse;
use App\Service\Http\Response;
use App\Service\Http\Session\Session;
use App\View\View;
use App\Service\Http\Request;

class HomeController
{

    private View $view;
    private PostRepository $postRepository;
    private Session $session;
    private ContactFormValidator $contactFormValidator;
    private Csrf $csrf;
    private Authentication $authentication;

    public function __construct(PostRepository $postRepository, ContactFormValidator $contactFormValidator, View $view, Session $session, Csrf $csrf, Authentication $authentication)
    {
        $this->postRepository = $postRepository;
        $this->contactFormValidator = $contactFormValidator;
        $this->view = $view;
        $this->session = $session;
        $this->csrf = $csrf;
        $this->authentication = $authentication;
    }

    public function displayHomepageAction(Request $request, EmailService $emailService): Response
    {
        if ($this->authentication->isAuthenticated()) {
            if ($this->authentication->isAdmin() || $this->authentication->isEditor()) {
                $authorizedUser = $this->authentication->getAuthenticatedUser();
            }
        }

        $posts = $this->postRepository->findBy([], null, 3, 0);

        $data = $request->request()->all();

        if ($request->getMethod() === 'POST') {
            if ($this->contactFormValidator->isValid($data) && $this->csrf->checkToken($data['csrfToken'])) {
                $mailer = $emailService->prepareEmail();
                $message = $emailService->createMessage('Sullivan Berger - Demande de contact', $data['email'], $data['message']);
                $mailer->send($message);

                $this->session->addFlashes('success', 'Merci pour votre message, je vous répondrai dans les meilleurs délais.');
                return new RedirectResponse('/', 302);
            } else {
                $oldRequest = $request->request()->all();
                $this->session->addFlashes('error', "Le formulaire n'est pas valide, merci de vérifier les informations renseignées.");
            }
        }

        $this->csrf->generateToken();

        return new Response($this->view->render([
            'template' => 'home',

            'data' => [
                'posts' => $posts,
                'request' => $oldRequest,
                'connected' => $this->session->get('user'),
                'csrfToken' => $this->session->get('csrfToken'),
                'authorizedUser' => $authorizedUser
            ],
        ]));
    }
}
