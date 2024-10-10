<?php

namespace FalconBaseServices\Providers;

use FalconBaseServices\Services\TemplateEngine\Implements\Blade\Blade;

class TemplateServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->container->singleton('template', Blade::class);
    }

    public function boot() {}
}
