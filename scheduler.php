<?php

require_once __DIR__.'/vendor/autoload.php';

use GO\Scheduler;

const FALCON_BASE_SERVICES_ROOT_DIR = __DIR__;
const FALCON_BASE_TIME_ZONE = "Asia/Tehran";
const FALCON_BASE_SERVICES_STORAGE_DIR = FALCON_BASE_SERVICES_ROOT_DIR.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR;
const FALCON_BASE_SERVICES_VIEWS_DIR = FALCON_BASE_SERVICES_STORAGE_DIR.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR;
const FALCON_BASE_SERVICES_CACHE_DIR = FALCON_BASE_SERVICES_STORAGE_DIR.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR;
const FALCON_BASE_SERVICES_CONFIG_DIR = FALCON_BASE_SERVICES_ROOT_DIR.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR;

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$container = \HesamMousavi\FalconContainer\FalconContainer::getInstance();
$container->runProviders(__DIR__.'/bootstrap/providers.php');

\define('FALCON_CONTAINER', $container);

// Create a new scheduler
$scheduler = new Scheduler();

// ... configure the scheduled jobs (see below) ...

// Let the scheduler execute jobs which are due.

$scheduler->run();
