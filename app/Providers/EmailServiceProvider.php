<?php

namespace FalconBaseServices\Providers;

use FalconBaseServices\Services\Sender\Implements\Email\PHPMailer;

class EmailServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->container->singleton('email', PHPMailer::class);
    }

    public function boot()
    {

    }
}
