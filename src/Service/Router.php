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
use App\Model\Repository\PostRepository;
use App\Model\Repository\CommentRepository;
use App\Model\Repository\UserRepository;
use App\Service\Database\MySQLDB;
use App\Service\Form\ContactFormValidator;
use App\Service\Http\Request;
use App\Service\Http\Response;
use App\Service\Http\Session\Session;
use App\View\View;
use Config\DotEnv;

// TODO cette classe router est un exemple très basic. Cette façon de faire n'est pas optimale
// TODO Le router ne devrait pas avoir la responsabilité de l'injection des dépendances
final class Router
{
    private MySQLDB $database;
    private View $view;
    private Request $request;
    private Session $session;

    public function __construct(Request $request)
    {
        (new DotEnv(__DIR__ . '/../../.env'))->load();

        $this->database = new MySQLDB(getenv('DATABASE_HOST'), getenv('DATABASE_NAME'), getenv('DATABASE_USER'), getenv('DATABASE_PASSWORD'));
        $this->session = new Session();
        $this->view = new View($this->session);
        $this->request = $request;
    }

    public function run(): Response
    {
        $action = $this->request->query()->has('action') ? $this->request->query()->get('action') : 'home';

        // *** @Route http://localhost:8000/?action=posts ***
        if ($action === 'posts') {
            $postRepo = new PostRepository();
            $controller = new PostController($postRepo, $this->view);

            return $controller->displayAllPostsAction();

        // *** @Route http://localhost:8000/?action=post&id=5 ***
        } elseif ($action === 'post' && $this->request->query()->has('id')) {
            $postRepo = new PostRepository();
            $controller = new PostController($postRepo, $this->view);

            $commentRepo = new CommentRepository();

            return $controller->displayOneAction((int) $this->request->query()->get('id'), $commentRepo);

        // *** @Route http://localhost:8000/?action=home ***
        } elseif ($action === 'home') {
            $postRepo = new PostRepository();
            $contactFormValidator = new ContactFormValidator();
            $controller = new HomeController($postRepo, $contactFormValidator,  $this->view, $this->session);

            return $controller->displayHomepageAction($this->request);

        // *** @Route http://localhost:8000/?action=login ***
        } elseif ($action === 'login') {
            $userRepo = new UserRepository();
            $controller = new UserController($userRepo, $this->view, $this->session);

            return $controller->loginAction($this->request);

        // *** @Route http://localhost:8000/?action=logout ***
        } elseif ($action === 'logout') {
            $userRepo = new UserRepository();
            $controller = new UserController($userRepo, $this->view, $this->session);

            return $controller->logoutAction();

            // *** @Route http://localhost:8000/?action=register ***
        } elseif ($action === 'register') {
            $userRepo = new UserRepository();
            $controller = new UserController($userRepo, $this->view, $this->session);

            return $controller->registerAction();

        // *** @Route http://localhost:8000/?action=admin ***
        } elseif ($action === 'admin') {
            $controller = new AdminHomeController($this->view);

            return $controller->displayAdminHomepageAction();

        // *** @Route http://localhost:8000/?action=admin_posts ***
        } elseif ($action === 'admin_posts') {
            $controller = new AdminPostController($this->view);

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
