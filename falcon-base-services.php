<?php

/**
 * Plugin Name: Falcon Base Services
 * Description: Base Services And Helper Functions For WordPress
 * Author: Seyed Hesam Mousavi
 * Version: 1.0.0
 */

use FalconBaseServices\Start;
use Psr\Log\LoggerInterface;
use FalconBaseServices\ContainerSingleton;
use FalconBaseServices\Services\TemplateEngine\Template;
use FalconBaseServices\Services\QueryBuilder\QueryBuilder;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__.'/vendor/autoload.php';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/storage/maintenance.php')) {
    require $maintenance;
    die();
}

define('FALCON_BASE_SERVICE_PLUGIN_ROOT_DIR', plugin_dir_path(__FILE__));
define('FALCON_BASE_SERVICE_PLUGIN_ROOT_URL', plugin_dir_url(__FILE__));
define("FALCON_BASE_TIME_ZONE", wp_timezone_string());

const BASE_SERVICE_PLUGIN_STORAGE_DIR = FALCON_BASE_SERVICE_PLUGIN_ROOT_DIR.'storage/';
const BASE_SERVICE_PLUGIN_VIEWS_DIR = BASE_SERVICE_PLUGIN_STORAGE_DIR.'/views/';
const BASE_SERVICE_PLUGIN_CACHE_DIR = BASE_SERVICE_PLUGIN_STORAGE_DIR.'/cache/';

//It is better that the .env file in top of public_html folder
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

define('BASE_CONTAINER', ContainerSingleton::getInstance());
define('LOGGER', BASE_CONTAINER->get(LoggerInterface::class));
define('TEMPLATE', BASE_CONTAINER->get(Template::class));

BASE_CONTAINER->get(QueryBuilder::class);

if (!function_exists('wp_get_current_user')) {
    include_once(ABSPATH."wp-includes/pluggable.php");
}

new Start();
