<?php

use App\Controller\Backoffice\AdminCommentController;
use App\Controller\Backoffice\AdminPostController;
use App\Controller\Backoffice\AdminUserController;
use App\Controller\Frontoffice\PostController;
use App\Controller\Frontoffice\UserController;
use App\Service\Container;

$container = Container::instance();

$container->registerService("base_home_controller", function (Container $container) {
    return new \App\Controller\Frontoffice\HomeController(
        $container->get("post_repository"),
        $container->get("contact_form_validator"),
        $container->get("view"),
        $container->get("session"),
        $container->get("csrf"),
        $container->get("authentication")
    );
});

$container->registerService("base_post_controller", function (Container $container) {
    return new PostController(
        $container->get("post_repository"),
        $container->get("user_repository"),
        $container->get("view"),
        $container->get("session"),
        $container->get("csrf"),
        $container->get("authentication")
    );
});

$container->registerService("base_user_controller", function (Container $container) {
   return new UserController(
       $container->get("user_repository"),
       $container->get("authentication"),
       $container->get("view"),
       $container->get("session"),
       $container->get("csrf"),
   );
});

$container->registerService("base_admin_post_controller", function (Container $container) {
    return new AdminPostController(
        $container->get("request"),
        $container->get("view"),
        $container->get("session"),
        $container->get("post_repository"),
        $container->get("user_repository"),
        $container->get("post_form_validator"),
        $container->get("authentication"),
        $container->get("csrf"),
    );
});

$container->registerService("base_admin_comment_controller", function (Container $container) {
    return new AdminCommentController(
        $container->get("request"),
        $container->get("view"),
        $container->get("session"),
        $container->get("authentication"),
        $container->get("comment_repository"),
        $container->get("csrf"),
        $container->get("user_repository")
    );
});

$container->registerService("base_admin_user_controller", function (Container $container) {
    return new AdminUserController(
        $container->get("view"),
        $container->get("user_repository"),
        $container->get("authentication"),
        $container->get("request"),
        $container->get("csrf"),
        $container->get("session"),
    );
});

$container->registerService("home_controller", function (Container $container) {
    $controller = $container->get("base_home_controller");

    return $controller->displayHomepageAction($container->get('request'), $container->get("email_service"));
});

$container->registerService("post_controller_index", function (Container $container) {
    /** @var PostController $controller */
    $controller = $container->get("base_post_controller");

    return $controller->displayAllPostsAction($container->get("pagination_service"));
});

$container->registerService("user_controller_login", function (Container $container) {
    /** @var UserController $controller */
    $controller = $container->get("base_user_controller");

    return $controller->loginAction($container->get("request"), $container->get("login_form_validator"));
});

$container->registerService("user_controller_logout", function (Container $container) {
    /** @var UserController $controller */
    $controller = $container->get("base_user_controller");

    return $controller->logoutAction();
});

$container->registerService("user_controller_register", function (Container $container) {
    /** @var UserController $controller */
    $controller = $container->get("base_user_controller");

    return $controller->registerAction($container->get("request"), $container->get("register_form_validator"), $container->get("email_service"));
});

$container->registerService("admin_post_controller_index", function (Container $container) {
    /** @var AdminPostController $controller */
    $controller = $container->get("base_admin_post_controller");

    return $controller->displayAdminPostAction();
});

$container->registerService("admin_post_controller_add", function (Container $container) {
    /** @var AdminPostController $controller */
    $controller = $container->get("base_admin_post_controller");

    return $controller->addPostAction();
});

$container->registerService("admin_comment_controller_index", function (Container $container) {
    /** @var AdminCommentController $controller */
    $controller = $container->get("base_admin_comment_controller");

    return $controller->displayAdminCommentAction();
});

$container->registerService("admin_user_controller_index", function (Container $container) {
    /** @var AdminUserController $controller */
    $controller = $container->get("base_admin_user_controller");

    return $controller->displayAdminUserAction();
});

$container->registerService("post_controller_show", function (Container $container) {
    return function ($id) use ($container) {
        /** @var PostController $controller */
        $controller = $container->get("base_post_controller");

        $request = $container->get("request");

        return $controller->displayOneAction(
            $container->get("request"),
            $id,
            $container->get("comment_repository"),
            $container->get("comment_form_validator")
        );
    };
});

$container->registerService("admin_post_controller_edit", function (Container $container) {
    return function ($id) use ($container) {
        /** @var AdminPostController $controller */
        $controller = $container->get("base_admin_post_controller");

        return $controller->editPostAction($id);
    };
});
