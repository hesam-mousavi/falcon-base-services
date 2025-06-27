<?php

namespace FalconBaseServices\Providers;


use HesamMousavi\FalconContainer\FalconServiceProvider;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Processor\GitProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\ProcessIdProcessor;

class LoggerServiceProvider extends FalconServiceProvider
{
    public function register(): void
    {
        $this->container->singleton('logger', function () {
            $level = Level::Info;
            if (defined('WP_DEBUG') && true === WP_DEBUG) {
                $level = Level::Debug;
            }

            $logger = new Logger('core');

            if (isset($_ENV['PROCESS_ID_PROCESSOR']) && strtolower($_ENV['PROCESS_ID_PROCESSOR']) == 'true') {
                $logger->pushProcessor(new ProcessIdProcessor());
            }
            if (isset($_ENV['GIT_PROCESSOR']) && strtolower($_ENV['GIT_PROCESSOR']) == 'true') {
                $logger->pushProcessor(new GitProcessor());
            }
            if (isset($_ENV['MEMORY_USAGE_PROCESSOR']) && strtolower($_ENV['MEMORY_USAGE_PROCESSOR']) == 'true') {
                $logger->pushProcessor(new MemoryUsageProcessor());
            }

            $output = "[%datetime%] %level_name%  : %message% %context% %extra%\n";
            $date_format = "y-M-d H:i:s";
            $formatter = new LineFormatter(
                $output, // Format of message in log
                $date_format, // Datetime format
                true, // allowInlineLineBreaks option, default false
                true,  // discard empty Square brackets in the end, default false
            );

            $file_name = FALCON_BASE_SERVICES_STORAGE_DIR . '/log/core.log';
            $max_file = $_ENV['LOGGER_MAX_FILES'] ?? 5;
            $rotating_handle = new RotatingFileHandler($file_name, $max_file, $level);
            $rotating_handle->setFormatter($formatter);
            $logger->setTimezone(new \DateTimeZone(FALCON_BASE_TIME_ZONE));
            $logger->pushHandler($rotating_handle);

            return $logger;
        });
    }

    public function boot()
    {
    }
}
