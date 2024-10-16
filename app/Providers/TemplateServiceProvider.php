<?php

namespace FalconBaseServices\Providers;

use FalconBaseServices\Services\TemplateEngine\Implements\Blade\Blade;
use HesamMousavi\FalconContainer\FalconServiceProvider;

class TemplateServiceProvider extends FalconServiceProvider
{
    public function register(): void
    {
        $this->container->singleton('template', Blade::class);
    }

    public function boot() {}
}
