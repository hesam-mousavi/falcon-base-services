<?php

namespace FalconBaseServices;

use FalconBaseServices\Services\CurrentUser;

class Routes
{
    use RegisterApiAjax;

    public function __construct()
    {
        $this->registerAjax();
        $this->registerApi();
    }

    public function ajaxList(): array
    {
        return [
            [
                'action' => 'login',
                'callback' => [$this, 'login'],
                'is_public' => true,
            ],
        ];
    }

    public function apiList(): array
    {
        return [
            'auth' => [
                [
                    'end_point' => 'login',
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'login'],
                    'permission' => function ($request) {
                        return true;
                    },
                ],
                [
                    'end_point' => 'getToken',
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'getToken'],
                    'permission' => function ($request) {
                        return CurrentUser::authorized();
                    },
                ],
                [
                    'end_point' => 'logout',
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'logout'],
                    'permission' => function ($request) {
                        return CurrentUser::authorized();
                    },
                ],
            ],
            'user' => [
                [
                    'end_point' => 'profile',
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => [$this, 'profile'],
                    'permission' => function ($request) {
                        return CurrentUser::authorized();
                    },
                ],
            ],
        ];
    }

    public function login(): void
    {
        BASE_CONTAINER->call(
            ['BaseService\Http\Controllers\Public\LoginController', 'login'],
        );
    }

    public function getToken(): void
    {
        BASE_CONTAINER->call(
            ['BaseService\Http\Controllers\Public\LoginController', 'getToken'],
        );
    }

    public function logout(): void
    {
        BASE_CONTAINER->call(
            ['BaseService\Http\Controllers\Public\LoginController', 'logout'],
        );
    }

    public function profile(): void
    {
        BASE_CONTAINER->call(
            ['BaseService\Http\Controllers\Public\UserController', 'profile'],
        );
    }

}
