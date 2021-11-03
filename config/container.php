<?php

use App\Model\Repository\CommentRepository;
use App\Service\Container;
use App\Service\DotEnv\DotEnv;

$container = Container::instance();

$container->registerService("database_connection", function (Container $container) {
    /** @var DotEnv $dotEnv */
    $dotEnv = $container->get("dotenv");
    return new App\Service\Database\MySQLDB($dotEnv->get('DATABASE_HOST'), $dotEnv->get('DATABASE_NAME'), $dotEnv->get('DATABASE_USER'), $dotEnv->get('DATABASE_PASSWORD'));
});

$container->registerService("session", function (Container $container) {
   return new \App\Service\Http\Session\Session();
});

$container->registerService("view", function (Container $container) {
   return new \App\View\View($container->get("session"));
});

$container->registerService("csrf", function (Container $container) {
    return new \App\Service\CSRF\Csrf($container->get("session"));
});

$container->registerService("user_repository", function (Container $container) {
   return new \App\Model\Repository\UserRepository($container->get("database_connection"));
});

$container->registerService("post_repository", function (Container $container) {
   return new \App\Model\Repository\PostRepository($container->get("database_connection"));
});

$container->registerService("contact_form_validator", function (Container $container) {
    return new \App\Service\Form\ContactFormValidator($container->get("session"));
});

$container->registerService("login_form_validator", function (Container $container) {
   return new \App\Service\Form\LoginFormValidator($container->get('user_repository'), $container->get("session"));
});

$container->registerService("register_form_validator", function (Container $container) {
   return new \App\Service\Form\RegisterFormValidator($container->get("session"), $container->get("user_repository"));
});

$container->registerService("post_form_validator", function (Container $container) {
   return new \App\Service\Form\PostFormValidator($container->get("session"));
});

$container->registerService("email_service", function (Container $container) {
    /** @var DotEnv $dotEnv */
    $dotEnv = $container->get("dotenv");
    return new \App\Service\Email\EmailService($dotEnv->get('EMAIL_SMTP'), $dotEnv->get('EMAIL_SMTP_PORT'), $dotEnv->get('EMAIL_USERNAME'), $dotEnv->get('EMAIL_PASSWORD'), $dotEnv->get('EMAIL_ADDRESS'));
});

$container->registerService('pagination_service', function (Container $container) {
   return new \App\Service\Pagination\PaginationService($container->get("database_connection"), $container->get("post_repository"), 5);
});

$container->registerService("authentication", function (Container $container) {
   return new App\Service\Authentication\Authentication($container->get("session"), $container->get("user_repository"));
});

$container->registerService("comment_repository", function (Container $container) {
   return new CommentRepository($container->get("database_connection"));
});

require __DIR__ . "/container/controller.php";
