<?php

declare(strict_types=1);

namespace  App\Service;

use App\Controller\Backoffice\AdminCommentController;
use App\Controller\Backoffice\AdminHomeController;
use App\Controller\Backoffice\AdminPostController;
use App\Controller\Backoffice\AdminUserController;
use App\Controller\Frontoffice\HomeController;
use App\Controller\Frontoffice\PostController;
use App\Controller\Frontoffice\UserController;
use App\Model\Entity\User;
use App\Model\Repository\PostRepository;
use App\Model\Repository\CommentRepository;
use App\Model\Repository\UserRepository;
use App\Service\CSRF\Csrf;
use App\Service\Database\MySQLDB;
use App\Service\Email\EmailService;
use App\Service\Form\PostFormValidator;
use App\Service\Form\CommentFormValidator;
use App\Service\Form\ContactFormValidator;
use App\Service\Form\LoginFormValidator;
use App\Service\Form\RegisterFormValidator;
use App\Service\Authentication\Authentication;
use App\Service\Http\RedirectResponse;
use App\Service\Http\Request;
use App\Service\Http\Response;
use App\Service\Http\Session\Session;
use App\Service\Pagination\PaginationService;
use App\View\View;
use App\Service\DotEnv\DotEnv;

// TODO cette classe router est un exemple très basic. Cette façon de faire n'est pas optimale
// TODO Le router ne devrait pas avoir la responsabilité de l'injection des dépendances
final class Router
{
    private MySQLDB $database;
    private View $view;
    private Request $request;
    private Session $session;
    private DotEnv $dotEnv;
    private EmailService $emailService;

    public function __construct(Request $request, DotEnv $dotEnv)
    {
        $this->dotEnv = $dotEnv;
        $this->database = new MySQLDB($this->dotEnv->get('DATABASE_HOST'), $this->dotEnv->get('DATABASE_NAME'), $this->dotEnv->get('DATABASE_USER'), $this->dotEnv->get('DATABASE_PASSWORD'));
        $this->emailService = new EmailService(
            $this->dotEnv->get('EMAIL_SMTP'),
            $this->dotEnv->get('EMAIL_SMTP_PORT'),
            $this->dotEnv->get('EMAIL_USERNAME'),
            $this->dotEnv->get('EMAIL_PASSWORD'),
            $this->dotEnv->get('EMAIL_ADDRESS')
        );
        $this->session = new Session();
        $this->view = new View($this->session);
        $this->request = $request;
    }

    public function run(): Response
    {
        $action = $this->request->query()->has('action') ? $this->request->query()->get('action') : 'home';

        // *** @Route http://localhost:8000/?action=posts ***
        if ($action === 'posts') {
            $postRepo = new PostRepository($this->database);
            $paginationService = new PaginationService($this->database, $postRepo, 3);
            $userRepo = new UserRepository($this->database);
            $controller = new PostController($postRepo, $userRepo, $this->view, $this->session);

            return $controller->displayAllPostsAction($paginationService);

        // *** @Route http://localhost:8000/?action=post&id=5 ***
        } elseif ($action === 'post' && $this->request->query()->has('id')) {
            $postRepo = new PostRepository($this->database);
            $userRepo = new UserRepository($this->database);
            $controller = new PostController($postRepo, $userRepo, $this->view, $this->session);
            $commentFormValidator = new CommentFormValidator($this->session);

            $commentRepo = new CommentRepository($this->database);

            return $controller->displayOneAction($this->request, (int) $this->request->query()->get('id'), $commentRepo, $commentFormValidator);

        // *** @Route http://localhost:8000/?action=home ***
        } elseif ($action === 'home') {
            $postRepo = new PostRepository($this->database);
            $contactFormValidator = new ContactFormValidator($this->session);
            $controller = new HomeController($postRepo, $contactFormValidator, $this->view, $this->session);
            $csrf = new Csrf($this->session);

            return $controller->displayHomepageAction($this->request, $this->emailService, $csrf);

        // *** @Route http://localhost:8000/?action=login ***
        } elseif ($action === 'login') {
            $userRepo = new UserRepository($this->database);
            $postRepo = new PostRepository($this->database);
            $authentication = new Authentication($this->session, $userRepo);
            $loginFormValidator = new LoginFormValidator($userRepo, $this->session);
            $controller = new UserController($userRepo, $postRepo, $authentication, $this->view, $this->session);

            return $controller->loginAction($this->request, $loginFormValidator);

        // *** @Route http://localhost:8000/?action=logout ***
        } elseif ($action === 'logout') {
            $userRepo = new UserRepository($this->database);
            $postRepo = new PostRepository($this->database);
            $authentication = new Authentication($this->session, $userRepo);
            $controller = new UserController($userRepo, $postRepo, $authentication, $this->view, $this->session);

            return $controller->logoutAction();

            // *** @Route http://localhost:8000/?action=register ***
        } elseif ($action === 'register') {
            $userRepo = new UserRepository($this->database);
            $postRepo = new PostRepository($this->database);
            $authentication = new Authentication($this->session, $userRepo);
            $registerFormValidator = new RegisterFormValidator($this->session, $userRepo);
            $controller = new UserController($userRepo, $postRepo, $authentication, $this->view, $this->session);


            return $controller->registerAction($this->request, $registerFormValidator, $this->emailService);

        // *** @Route http://localhost:8000/?action=admin ***
        } elseif ($action === 'admin') {
            $userRepo = new UserRepository($this->database);
            $authentication = new Authentication($this->session, $userRepo);
            $controller = new AdminHomeController($this->view, $authentication);

            return $controller->displayAdminHomepageAction();

        // *** @Route http://localhost:8000/?action=admin_posts ***
        } elseif ($action === 'admin_posts') {
            $postRepo = new PostRepository($this->database);
            $userRepo = new UserRepository($this->database);
            $postFormValidator = new PostFormValidator($this->session);
            $authentication = new Authentication($this->session, $userRepo);
            $controller = new AdminPostController($this->request, $this->view, $this->session, $postRepo, $userRepo, $postFormValidator, $authentication);

            return $controller->displayAdminPostAction();

        // *** @Route http://localhost:8000/?action=admin_add_post ***
        } elseif ($action === 'admin_add_post') {
            $postRepo = new PostRepository($this->database);
            $userRepo = new UserRepository($this->database);
            $postFormValidator = new PostFormValidator($this->session);
            $authentication = new Authentication($this->session, $userRepo);
            $controller = new AdminPostController($this->request, $this->view, $this->session, $postRepo, $userRepo, $postFormValidator, $authentication);

            return $controller->addPostAction($this->request);

        // *** @Route http://localhost:8000/?action=admin_edit_post&id=1 ***
        } elseif ($action === 'admin_edit_post' && $this->request->query()->has('id')) {
            $postRepo = new PostRepository($this->database);
            $userRepo = new UserRepository($this->database);
            $postFormValidator = new PostFormValidator($this->session);
            $authentication = new Authentication($this->session, $userRepo);
            $controller = new AdminPostController($this->request, $this->view, $this->session, $postRepo, $userRepo, $postFormValidator, $authentication);

            return $controller->editPostAction((int) $this->request->query()->get('id'));
        // *** @Route http://localhost:8000/?action=admin_comments ***
        } elseif ($action === 'admin_comments') {
            $commentRepo = new CommentRepository($this->database);
            $userRepo = new UserRepository($this->database);
            $authentication = new Authentication($this->session, $userRepo);
            $controller = new AdminCommentController($this->request, $this->view, $this->session, $authentication, $commentRepo);

            return $controller->displayAdminCommentAction();

        // *** @Route http://localhost:8000/?action=admin_users ***
        } elseif ($action === 'admin_users') {
            $userRepo = new UserRepository($this->database);
            $authentication = new Authentication($this->session, $userRepo);
            $controller = new AdminUserController($this->view, $userRepo, $authentication);

            return $controller->displayAdminUserAction();
        } else {
            // TODO : Pas de redirection sur 404 ?
            return new RedirectResponse('index.php?action=home', 404);
        }
    }
}
