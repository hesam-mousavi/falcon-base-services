<?php

function falconLogger()
{
    return FALCON_CONTAINER->get('logger');
}

function falconDB()
{
    return FALCON_CONTAINER->get('db');
}

function falconTemplate()
{
    return FALCON_CONTAINER->get('template');
}

function falconEmail()
{
    return FALCON_CONTAINER->get('email');
}

function falconEnv($key = null)
{
    if (!is_null($key)) {
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }

        return null;
    }

    return $_ENV;
}

function falconSetEnv($key, $value): void
{
    $_ENV[$key] = $value;
}

function falconConfig($file, $key = null, $folder_path = null)
{
    $folder = $folder_path ? rtrim($folder_path, '\\/').DIRECTORY_SEPARATOR : BASE_SERVICE_PLUGIN_CONFIG_DIR;
    $file = $folder.$file.'.php';

    if (file_exists($file)) {
        $config = require_once $file;
        if (!is_null($key)) {
            if (array_key_exists($key, $config)) {
                return $config[$key];
            }

            falconLogger()->warning("'$key' config key not exist in $file");
            return null;
        }

        return $config;
    }

    falconLogger()->warning("$file config file not exist");
    return null;
}
