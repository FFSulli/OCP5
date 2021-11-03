<?php

use App\Service\Router;

return [
    [
        'path' => function (string $path) {
            return $path === '/';
        },
        'methods' => ['GET'],
        'handler' => function (\App\Service\Container $container) {
            return $container->get('home_controller');
        }
    ],
    [
        'path' => function (string $path) {
            return $path === '/posts';
        },
        'methods' => ['GET'],
        'handler' => function (\App\Service\Container $container) {
            return $container->get('post_controller_index');
        }
    ],
    [
        'path' => function (string $path) {
            return $path === '/register';
        },
        'methods' => ['GET'],
        'handler' => function (\App\Service\Container $container) {
            return $container->get('user_controller_register');
        }
    ],
    [
        'path' => function (string $path) {
            return $path === '/login';
        },
        'methods' => ['GET'],
        'handler' => function (\App\Service\Container $container) {
            return $container->get('user_controller_login');
        }
    ],
    [
        'path' => function (string $path) {
            return $path === '/logout';
        },
        'methods' => ['GET'],
        'handler' => function (\App\Service\Container $container) {
            return $container->get('user_controller_logout');
        }
    ],
    [
        'path' => function (string $path) {
            return $path === '/admin';
        },
        'methods' => ['GET'],
        'handler' => function (\App\Service\Container $container) {
            return $container->get('admin_home_controller_index');
        }
    ],
    [
        'path' => function (string $path) {
            return $path === '/admin/posts';
        },
        'methods' => ['GET'],
        'handler' => function (\App\Service\Container $container) {
            return $container->get('admin_post_controller_index');
        }
    ],
    [
        'path' => function (string $path) {
            return $path === '/admin/posts/add';
        },
        'methods' => ['GET'],
        'handler' => function (\App\Service\Container $container) {
            return $container->get('admin_post_controller_add');
        }
    ],
    [
        'path' => function (string $path) {
            return $path === '/admin/comments';
        },
        'methods' => ['GET'],
        'handler' => function (\App\Service\Container $container) {
            return $container->get('admin_comment_controller_index');
        }
    ],
    [
        'path' => function (string $path) {
            return $path === '/admin/users';
        },
        'methods' => ['GET'],
        'handler' => function (\App\Service\Container $container) {
            return $container->get('admin_user_controller_index');
        }
    ],
    [
        'path' => function (string $path) {
            return $path === ('/posts');
        },
        'methods' => ['GET'],
        'handler' => function (\App\Service\Container $container) {
            /** @var \App\Service\Http\Request $request */
            $request = $container->get('request');
            $id = 4; // Faire des trucs avec la requete
            $handler = $container->get("with_id_controller");

            return $handler($id);
        }
    ],
];
