<?php

namespace FalconBaseServices\Services;


use FalconBaseServices\Helper\Time;

class Logger
{
    private string $logDir;
    private int $keepDays;
    private string $currentLogFile;
    private string $level;

    public function __construct(string $logDir = null)
    {
        $this->level = 'info';
        if (defined('WP_DEBUG') && true === WP_DEBUG) {
            $this->level = 'debug';
        }

        $this->logDir = $logDir ?? WP_CONTENT_DIR . '/logs';
        $this->keepDays = env('LOGGER_MAX_FILES', 30);
        $this->ensureLogDirectory();
        $this->currentLogFile = $this->logDir . '/' .Time::Translate(Time::now(),'Y-M-d', 'en_US') . '.log';
        $this->cleanupOldLogs();
    }

    private function ensureLogDirectory(): void
    {
        if (!file_exists($this->logDir)) {
            mkdir($this->logDir, 0755, true);
        }
    }

    private function cleanupOldLogs(): void
    {
        $files = glob($this->logDir . '/*.log');
        if (!$files) return;

        usort($files, function ($a, $b) {
            return filemtime($b) <=> filemtime($a);
        });

        $filesToDelete = array_slice($files, $this->keepDays);
        foreach ($filesToDelete as $file) {
            @unlink($file);
        }
    }

    public function log(string $level, string $message, array $context = []): void
    {
        $msg = '[' . Time::Translate(Time::now(),'yyyy-MM-dd HH:mm:ss', 'en_US'). "] [$level] $message";
        if (!empty($context)) {
            $msg .= ' | ' . json_encode($context, JSON_UNESCAPED_UNICODE);
        }

        file_put_contents($this->currentLogFile, $msg . PHP_EOL, FILE_APPEND);
    }

    public function debug($message, array $context = [])
    {
        if ($this->level == 'debug')
            $this->log('DEBUG', $message, $context);
    }

    public function info($message, array $context = [])
    {
        $this->log('INFO', $message, $context);
    }

    public function warning($message, array $context = [])
    {
        $this->log('WARNING', $message, $context);
    }

    public function error($message, array $context = [])
    {
        $this->log('ERROR', $message, $context);
    }
}

