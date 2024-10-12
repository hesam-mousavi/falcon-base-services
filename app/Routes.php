<?php

namespace FalconBaseServices;

use FalconBaseServices\Http\Controllers\Public\LoginController;
use FalconBaseServices\Http\Controllers\Public\UserController;
use FalconBaseServices\Services\CurrentUser;
use WP_REST_Server;

class Routes
{
    use RegisterApiAjax;

    public function register(): void
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
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'login'],
                    'permission' => function ($request) {
                        return true;
                    },
                ],
                [
                    'end_point' => 'getToken',
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'getToken'],
                    'permission' => function ($request) {
                        return CurrentUser::authorized();
                    },
                ],
                [
                    'end_point' => 'logout',
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'logout'],
                    'permission' => function ($request) {
                        return CurrentUser::authorized();
                    },
                ],
            ],
            'user' => [
                [
                    'end_point' => 'profile',
                    'methods' => WP_REST_Server::READABLE,
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
        FALCON_CONTAINER->getMethod(LoginController::class, 'login');
    }

    public function getToken(): void
    {
        FALCON_CONTAINER->getMethod(LoginController::class, 'getToken');
    }

    public function logout(): void
    {
        FALCON_CONTAINER->getMethod(LoginController::class, 'logout');
    }

    public function profile(): void
    {
        FALCON_CONTAINER->getMethod(UserController::class, 'profile');
    }

}
