<?php

namespace FalconBaseServices;

trait RegisterApiAjax
{

    public function restApiInitCallback(): void
    {
        foreach ($this->apiList() as $category => $routes) {
            foreach ($routes as $route) {
                $c = ($route['end_point'] == '' || $route['end_point'] == '/') ? '' : '/'.$category;
                $r = ($route['end_point'] == '' || $route['end_point'] == '/') ? $category : $route['end_point'];
                register_rest_route(
                    $_ENV['START_API_URI'].$c,
                    $r,
                    [
                        'methods' => $route['methods'],
                        'callback' => $route['callback'],
                        'permission_callback' => isset($route['permission']) ? $route['permission'] : '__return_true',
                    ],
                );
            }
        }
    }

    private function registerAjax(): void
    {
        try {
            foreach ($this->ajaxList() as $ajax) {
                $action = "wp_ajax_{$ajax['action']}";
                add_action($action, $ajax['callback']);

                if (array_key_exists('is_public', $ajax)
                    && $ajax['is_public'] == true
                ) {
                    add_action("wp_ajax_nopriv_{$ajax['action']}", $ajax['callback']);
                }
            }
        } catch (\Exception $exception) {
            LOGGER->alert(
                $exception->getMessage(),
                [
                    'class' => __CLASS__,
                    'function' => __FUNCTION__,
                    'line' => __LINE__,
                ],
            );
        }
    }

    private function registerApi(): void
    {
        if (method_exists($this, 'apiList')) {
            try {
                add_action('rest_api_init', [$this, 'restApiInitCallback']);
            } catch (\Exception $exception) {
                LOGGER->alert(
                    $exception->getMessage(),
                    [
                        'class' => __CLASS__,
                        'function' => __FUNCTION__,
                        'line' => __LINE__,
                    ],
                );
            }
        } else {
            LOGGER->warning(
                "apiList() method not exist",
                [
                    'class' => __CLASS__,
                    'function' => __FUNCTION__,
                    'line' => __LINE__,
                ],
            );
        }
    }
}
