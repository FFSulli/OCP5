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

final class Router
{
    private MySQLDB $database;
    private View $view;
    private Request $request;
    private Session $session;
    private DotEnv $dotEnv;
    private EmailService $emailService;
    private Container $container;

    public function __construct(Request $request, DotEnv $dotEnv)
    {
        $this->container = Container::instance();
        $this->container->set("dotenv", $dotEnv);
        $this->container->set("request", $request);
        $this->container->get('database_connection');

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

        $routes = $this->loadRoutes();

        $controller = null;

        foreach ($routes as $route) {
            if ($route->match($this->request->getPath())) {
                $handler = $route->getHandler();
                return $handler($this->container);
                break;
            }
        }

        if ($controller === null) {
            return new RedirectResponse('/', 404);
        }
    }


    /**
     * @return Route[]
     */
    private function loadRoutes(): array
    {
        $raw_routes = require __DIR__ . '/../../config/routes.php';

        $routes = [];

        foreach ($raw_routes as $raw_route) {
            $route = new Route($raw_route['path'], $raw_route['methods'], $raw_route['handler']);
            $routes[] = $route;
        }

        return $routes;
    }
}
