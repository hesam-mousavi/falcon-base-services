<?php

namespace FalconBaseServices\Http\Controllers\Public;

use FalconBaseServices\Helper\Response;
use FalconBaseServices\Http\Requests\LoginRequest;
use FalconBaseServices\Services\CurrentUser;

class LoginController
{

    public function login(LoginRequest $request): void
    {
        CurrentUser::login($request);
    }

    public function getToken(): void
    {
        $jwt_token = get_user_meta(CurrentUser::id(), $_ENV['JWT_META_KEY']);
        Response::json(data: ["jwt_token" => $jwt_token]);
    }

    public function logout(): void
    {
        delete_user_meta(CurrentUser::id(), $_ENV['JWT_META_KEY']);
        do_action("wp_logout", CurrentUser::id());

        Response::ok();
    }
}
