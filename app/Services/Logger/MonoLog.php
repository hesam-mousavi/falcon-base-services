<?php

namespace FalconBaseServices\Services\Logger;

use Psr\Log\LoggerInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Processor\GitProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\ProcessIdProcessor;

class MonoLog implements LoggerInterface
{

    protected Logger $logger;

    public function __construct()
    {
        $level = Level::Info;
        if (defined('WP_DEBUG') && true === WP_DEBUG) {
            $level = Level::Debug;
        }

        $this->logger = new Logger('core');

//        $this->logger->pushProcessor(new ProcessIdProcessor());
//        $this->logger->pushProcessor(new GitProcessor());
//        $this->logger->pushProcessor(new MemoryUsageProcessor());

        $output = "%level_name% | %datetime% > %message% | %context% %extra%\n";
        $date_format = "Y-n-j, g:i:s a";
        $formatter = new LineFormatter(
            $output, // Format of message in log
            $date_format, // Datetime format
            true, // allowInlineLineBreaks option, default false
            true,  // discard empty Square brackets in the end, default false
        );

        $file_name = BASE_SERVICE_PLUGIN_STORAGE_DIR.'/log/core.log';

        $rotating_handle = new RotatingFileHandler($file_name, $_ENV['LOGGER_MAX_FILES'], $level);
        $rotating_handle->setFormatter($formatter);
        $this->logger->setTimezone(new \DateTimeZone(FALCON_BASE_TIME_ZONE));
        $this->logger->pushHandler($rotating_handle);
    }

    public function debug(string|\Stringable $message, array $context = []): void
    {
        $this->logger->debug($message, $context);
    }

//    Emergency: system is unusable
//    Action must be taken immediately. Example: Entire website down, database unavailable, etc.
//    This should trigger the SMS alerts and wake you up.

    public function emergency(string|\Stringable $message, array $context = []): void
    {
        $this->logger->emergency($message, $context);
    }

    //Critical conditions. Example: Application component unavailable, unexpected exception.

    public function alert(string|\Stringable $message, array $context = []): void
    {
        $this->logger->alert($message, $context);
    }

    //Runtime errors that do not require immediate action but should typically be logged and monitored.

    public function critical(string|\Stringable $message, array $context = []): void
    {
        $this->logger->critical($message, $context);
    }

    //Exceptional occurrences that are not errors.
    // Examples: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong.

    public function error(string|\Stringable $message, array $context = []): void
    {
        $this->logger->error($message, $context);
    }

    //Normal but significant events.

    public function warning(string|\Stringable $message, array $context = []): void
    {
        $this->logger->warning($message, $context);
    }

    //Interesting events. Examples: User logs in, SQL logs.

    public function notice(string|\Stringable $message, array $context = []): void
    {
        $this->logger->notice($message, $context);
    }

    //Detailed debug information.

    public function info(string|\Stringable $message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }

    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $this->logger->log($level, $message, $context);
    }

    public function get()
    {
        return $this->logger;
    }

}
