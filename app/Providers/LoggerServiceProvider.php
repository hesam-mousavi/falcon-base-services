<?php

namespace FalconBaseServices\Providers;


use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Processor\GitProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\ProcessIdProcessor;

class LoggerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->container->singleton('logger', function () {
            $level = Level::Info;
            if (defined('WP_DEBUG') && true === WP_DEBUG) {
                $level = Level::Debug;
            }

            $logger = new Logger('core');

            if ($_ENV['PROCESS_ID_PROCESSOR']) {
                $logger->pushProcessor(new ProcessIdProcessor());
            }
            if ($_ENV['GIT_PROCESSOR']) {
                $logger->pushProcessor(new GitProcessor());
            }
            if ($_ENV['MEMORY_USAGE_PROCESSOR']) {
                $logger->pushProcessor(new MemoryUsageProcessor());
            }

            $output = "[%datetime%] %level_name%  : %message% %context% %extra%\n";
            $date_format = "y-M-d H:m:s";
            $formatter = new LineFormatter(
                $output, // Format of message in log
                $date_format, // Datetime format
                true, // allowInlineLineBreaks option, default false
                true,  // discard empty Square brackets in the end, default false
            );

            $file_name = BASE_SERVICE_PLUGIN_STORAGE_DIR.'/log/core.log';

            $rotating_handle = new RotatingFileHandler($file_name, $_ENV['LOGGER_MAX_FILES'], $level);
            $rotating_handle->setFormatter($formatter);
            $logger->setTimezone(new \DateTimeZone(FALCON_BASE_TIME_ZONE));
            $logger->pushHandler($rotating_handle);

            return $logger;
        });
    }

    public function boot() {}
}
