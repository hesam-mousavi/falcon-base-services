<?php

namespace FalconBaseServices\Providers;

use FalconBaseServices\Services\Sender\Implements\Email\PHPMailer;
use HesamMousavi\FalconContainer\FalconServiceProvider;

class EmailServiceProvider extends FalconServiceProvider
{
    public function register(): void
    {
        $this->container->singleton('email', PHPMailer::class);
    }

    public function boot()
    {

    }
}
