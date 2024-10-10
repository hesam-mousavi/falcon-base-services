<?php

/**
 *  Plugin Name: Falcon Base Services
 *  Description: Most Used Base Services And Helper Functions For WordPress
 *  Version: 2.0.0
 *  Author: Seyed Hesam Mousavi
 *  License: MIT
 *  License URI: https://opensource.org/licenses/MIT
 *  Email: hesam.mousavi@hotmail.com
 *
 *  This plugin provides a set of base services and helper functions to enhance
 *  WordPress development. It aims to bring service container-provider, Monolog, PHPMailer and some of the powerful
 *  features of Laravel, such as Query Builder and Eloquent, Blade, Validation and ... into WordPress environment.
 *
 *  Features:
 *  - Query Builder: Simplified database queries.
 *  - Eloquent ORM: Object-relational mapping for better data handling.
 *  - Blade-like Template Engine: Powerful templating capabilities.
 *  - Monolog: Comprehensive logging for debugging and monitoring.
 *  - PHPMailer: Easy and flexible email sending capabilities.
 *  - Service Container and Service Provider: Efficient dependency injection and service management.
 *  - Additional Helper Functions: Various utilities to speed up development.
 *
 *  Usage:
 *  - After install in mu-plugins folder, the plugin will automatically load the necessary files and set up the
 *    environment.
 *  - Refer to the documentation for detailed usage examples and best practices.
 *
 *  Support:
 *  - For support and contributions, please visit the GitHub repository:
 *    https://github.com/hesam-mousavi/falcon-base-services
 *  - Issues and pull requests are welcome.
 *
 * @package FalconBaseServices
 * /
 */

use FalconBaseServices\Start;

// If this file is called directly, abort.
if (!\defined('ABSPATH')) {
    exit;
}

require_once __DIR__.'/vendor/autoload.php';

// Determine if the application is in maintenance mode...
if (\file_exists($maintenance = __DIR__.'/storage/maintenance.php')) {
    require $maintenance;
    die();
}

\define('FALCON_BASE_SERVICE_PLUGIN_ROOT_DIR', plugin_dir_path(__FILE__));
\define('FALCON_BASE_SERVICE_PLUGIN_ROOT_URL', plugin_dir_url(__FILE__));
\define("FALCON_BASE_TIME_ZONE", wp_timezone_string());

const BASE_SERVICE_PLUGIN_STORAGE_DIR = FALCON_BASE_SERVICE_PLUGIN_ROOT_DIR.'storage/';
const BASE_SERVICE_PLUGIN_VIEWS_DIR = BASE_SERVICE_PLUGIN_STORAGE_DIR.'/views/';
const BASE_SERVICE_PLUGIN_CACHE_DIR = BASE_SERVICE_PLUGIN_STORAGE_DIR.'/cache/';

//It is better that the .env file in top of public_html folder
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$container = \FalconBaseServices\Services\FalconContainer::getInstance();
$container->runProviders();

\define('FALCON_CONTAINER', $container);

if (!\function_exists('wp_get_current_user')) {
    include_once(ABSPATH."wp-includes/pluggable.php");
}

new Start();
