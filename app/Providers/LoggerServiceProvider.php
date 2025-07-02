<?php

namespace FalconBaseServices\Providers;


use FalconBaseServices\Services\Logger;
use HesamMousavi\FalconContainer\FalconServiceProvider;

class LoggerServiceProvider extends FalconServiceProvider
{
    public function register(): void
    {
        $this->container->singleton('logger', function () {
            return new Logger(FALCON_BASE_SERVICES_STORAGE_DIR . 'log');
        });
    }

    public function boot()
    {
    }
}
