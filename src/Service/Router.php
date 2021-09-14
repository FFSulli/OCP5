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
use App\Service\Database\MySQLDB;
use App\Service\Email\EmailService;
use App\Service\Form\CommentFormValidator;
use App\Service\Form\ContactFormValidator;
use App\Service\Form\LoginFormValidator;
use App\Service\Form\RegisterFormValidator;
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

    public function __construct(Request $request, DotEnv $dotEnv)
    {
        $this->dotEnv = $dotEnv;
        $this->database = new MySQLDB($this->dotEnv->get('DATABASE_HOST'), $this->dotEnv->get('DATABASE_NAME'), $this->dotEnv->get('DATABASE_USER'), $this->dotEnv->get('DATABASE_PASSWORD'));
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
            $emailService = new EmailService(
                $this->dotEnv->get('EMAIL_SMTP'),
                $this->dotEnv->get('EMAIL_SMTP_PORT'),
                $this->dotEnv->get('EMAIL_USERNAME'),
                $this->dotEnv->get('EMAIL_PASSWORD'),
                $this->dotEnv->get('EMAIL_ADDRESS')
            );

            return $controller->displayHomepageAction($this->request, $emailService);

        // *** @Route http://localhost:8000/?action=login ***
        } elseif ($action === 'login') {
            $userRepo = new UserRepository($this->database);
            $postRepo = new PostRepository($this->database);
            $loginFormValidator = new LoginFormValidator($userRepo, $this->session);
            $controller = new UserController($userRepo, $postRepo, $this->view, $this->session);

            return $controller->loginAction($this->request, $loginFormValidator);

        // *** @Route http://localhost:8000/?action=logout ***
        } elseif ($action === 'logout') {
            $userRepo = new UserRepository($this->database);
            $postRepo = new PostRepository($this->database);
            $controller = new UserController($userRepo, $postRepo, $this->view, $this->session);

            return $controller->logoutAction();

            // *** @Route http://localhost:8000/?action=register ***
        } elseif ($action === 'register') {
            $userRepo = new UserRepository($this->database);
            $postRepo = new PostRepository($this->database);
            $registerFormValidator = new RegisterFormValidator($this->session);
            $controller = new UserController($userRepo, $postRepo, $this->view, $this->session);

            return $controller->registerAction($this->request, $registerFormValidator);

        // *** @Route http://localhost:8000/?action=admin ***
        } elseif ($action === 'admin') {
            $controller = new AdminHomeController($this->view);

            return $controller->displayAdminHomepageAction();

        // *** @Route http://localhost:8000/?action=admin_posts ***
        } elseif ($action === 'admin_posts') {
            $postRepo = new PostRepository($this->database);
            $controller = new AdminPostController($this->view, $postRepo);

            return $controller->displayAdminPostAction();

        // *** @Route http://localhost:8000/?action=admin_comments ***
        } elseif ($action === 'admin_comments') {
            $controller = new AdminCommentController($this->view);

            return $controller->displayAdminCommentAction();

        // *** @Route http://localhost:8000/?action=admin_users ***
        } elseif ($action === 'admin_users') {
            $controller = new AdminUserController($this->view);

            return $controller->displayAdminUserAction();
        } else {
            return new Response("Error 404 - cette page n'existe pas<br><a href='index.php?action=posts'>Aller Ici</a>", 404);
        }
    }
}
