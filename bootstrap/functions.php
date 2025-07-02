<?php

function logger()
{
    return FALCON_CONTAINER->get('logger');
}

function db()
{
    return FALCON_CONTAINER->get('db');
}

function template()
{
    return FALCON_CONTAINER->get('template');
}

function email()
{
    return FALCON_CONTAINER->get('email');
}


function setEnv($key, $value): void
{
    $_ENV[$key] = $value;
}

function config($file, $key, $folder_path = null)
{
    $folder = $folder_path ? rtrim($folder_path, '\\/').DIRECTORY_SEPARATOR : FALCON_BASE_SERVICES_CONFIG_DIR;
    $file = $folder.$file.'.php';

    if (file_exists($file)) {
        $config = require_once $file;
        if (!empty($key)) {
            if (array_key_exists($key, $config)) {
                return $config[$key];
            }

            logger()->warning("'$key' config key not exist in $file");
            return null;
        }

        return $config;
    }

    logger()->warning("$file config file not exist");
    return null;
}
