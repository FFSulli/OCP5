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

            return 1 === preg_match("#^/posts/[0-9]+$#", $path);
        },
        'methods' => ['GET'],
        'handler' => function (\App\Service\Container $container) {
            /** @var \App\Service\Http\Request $request */
            $request = $container->get('request');
            $path_array = explode("/", $request->getPath());
            $id = end($path_array);
            $handler = $container->get("post_controller_show");

            return $handler($id);
        }
    ],
    [
        'path' => function (string $path) {

            return 1 === preg_match("#^/admin/posts/edit/[0-9]+$#", $path);
        },
        'methods' => ['GET'],
        'handler' => function (\App\Service\Container $container) {
            /** @var \App\Service\Http\Request $request */
            $request = $container->get('request');
            $path_array = explode("/", $request->getPath());
            $id = end($path_array);
            $handler = $container->get("admin_post_controller_edit");

            return $handler($id);
        }
    ],
];
