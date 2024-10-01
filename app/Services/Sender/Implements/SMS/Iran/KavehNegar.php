<?php

namespace FalconBaseServices\Services\Sender\Implements\SMS\Iran;

use FalconBaseServices\Enum\HTTPStatus;
use FalconBaseServices\Services\Sender\Contracts\SMS;

class KavehNegar implements SMS
{

    public function send(string $receptor, string $message): bool
    {
        try {
            $body = ['receptor' => $receptor, 'message' => $message, 'sender' => $_ENV['KAVEH_NEGAR_SENDER']];

            $response = wp_remote_post(
                $_ENV['KAVEH_NEGAR_API_URL'].'/'.$_ENV['KAVEH_NEGAR_API_KEY'].'/'.$_ENV['KAVEH_NEGAR_API_END_POINT'],
                ['body' => $body],
            );

            if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != HTTPStatus::OK->value) {
                $arrayData = json_decode(wp_remote_retrieve_body($response), true);

                if (
                    !empty($arrayData['return']) &&
                    !empty($arrayData['entries']) &&
                    $arrayData['return']['status'] == HTTPStatus::OK->value
                ) {
                    return true;
                }

                LOGGER->warning('KavehNegar cant send SMS', ['data' => json_encode($arrayData)]);
                return false;
            }

            return true;
        } catch (\Exception $exception) {
            LOGGER->error('KavehNegar cant send SMS', ['exception message' => $exception->getMessage()]);
            return false;
        }
    }
}
