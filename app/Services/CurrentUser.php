<?php

namespace FalconBaseServices\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use FalconBaseServices\Helper\Time;
use FalconBaseServices\Helper\Request;
use FalconBaseServices\Helper\Response;
use FalconBaseServices\Http\Requests\LoginRequest;

class CurrentUser
{
    public static function login(LoginRequest $request)
    {
        $user = get_user_by('login', $request->email);

        if ($user) {
            if (wp_check_password($request->password, $user->user_pass, $user->ID)) {
                $credentials = [
                    'user_login' => $request->email,
                    'user_password' => $request->password,
                    'remember' => isset($request->remember) ? 1 : 0,
                ];

                $user = wp_signon($credentials, is_ssl());
                $user = wp_set_current_user($user->ID, $user->user_login);

                $payload = [
                    "user_id" => $user->ID,
                    "time" => Time::now(),
                ];

                $jwt_token = JWT::encode($payload, $_ENV['JWT_SECRET_KEY'], $_ENV['JWT_ALG']);
                update_user_meta($user->ID, $_ENV['JWT_META_KEY'], $jwt_token);

                if (Request::isAjax()) {
                    $current_user['profile'] = self::profile();
                    Response::json(data: ['profile' => $current_user]);
                } else {
                    Response::json(data: ['jwt_token' => $jwt_token]);
                }
            } else {
                Response::badRequest();
            }
        } else {
            Response::notFound();
        }
    }

    public static function profile()
    {
        $user = self::user();

        return [
            'id' => $user->ID,
            'user_login' => $user->user_login,
            'user_email' => $user->user_email,
            'user_url' => $user->user_url,
            'user_nicename' => $user->user_nicename,
            'user_registered' => $user->user_registered,
            'user_display_name' => $user->display_name,
            'role' => $user->roles[0],
            'has_password' => !empty($user->user_pass),
        ];
    }

    public static function user(): ?\WP_User
    {
        return wp_get_current_user();
    }

    public static function summaryProfile(): array
    {
        $user = self::user();

        return [
            'id' => $user->ID,
            'user_login' => $user->user_login,
            'user_display_name' => $user->display_name,
            'role' => $user->roles[0],
        ];
    }

    public static function authorized(): bool
    {
        if (self::id()) {
            $authorization_header_value = Request::headers("Authorization");

            if ($authorization_header_value !== []) {
                $meta_key = get_user_meta(self::id(), $_ENV['JWT_META_KEY']);
                if (!$meta_key) {
                    return false;
                } else {
                    $authorization_header_value_array = explode(' ', $authorization_header_value);
                    $token = end($authorization_header_value_array);
                    $decoded_token = JWT::decode($token, new Key($_ENV['JWT_SECRET_KEY'], $_ENV['JWT_ALG']));

                    if ($decoded_token->user_id != self::id()) {
                        return false;
                    }
                }
            }

            return true;
        }

        return false;
    }

    public static function id(): int
    {
        return wp_get_current_user()->ID;
    }

    public static function displayName(): string
    {
        return wp_get_current_user()->display_name;
    }

    public static function roles(): array
    {
        return wp_get_current_user()->roles;
    }

    public static function email(): string
    {
        return wp_get_current_user()->user_email;
    }

    public static function registeredDate(): string
    {
        return wp_get_current_user()->user_registered;
    }

    public static function pass(): string
    {
        return wp_get_current_user()->user_pass;
    }

    public static function caps(): array
    {
        $caps = [];
        foreach (wp_get_current_user()->allcaps as $cap => $enabled) {
            if ($enabled) {
                $caps[] = $cap;
            }
        }

        return $caps;
    }

    public static function level(): int
    {
        return wp_get_current_user()->user_level;
    }

    public static function removeRole(string $role): void
    {
        wp_get_current_user()->remove_role($role);
    }

    public static function firstName(): string
    {
        return wp_get_current_user()->first_name;
    }

    public static function lastName(): string
    {
        return wp_get_current_user()->last_name;
    }

    public static function hasCap(string $capability): bool
    {
        foreach (wp_get_current_user()->allcaps as $cap => $enabled) {
            if ($cap == $capability && $enabled) {
                return true;
            }
        }

        return false;
    }

    public static function hasRole(string $role): bool
    {
        foreach (wp_get_current_user()->roles as $user_role) {
            if ($user_role == $role) {
                return true;
            }
        }

        return false;
    }

}
