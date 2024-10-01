<?php

use function DI\create;

return [
    // Bind an interface to an implementation
    \FalconBaseServices\Services\Sender\Contracts\Email::class => create(
        \FalconBaseServices\Services\Sender\Implements\Email\PHPMailer::class,
    ),
    \FalconBaseServices\Services\TemplateEngine\Template::class => create(
        \FalconBaseServices\Services\TemplateEngine\Implements\Blade\Blade::class,
    ),
    \Psr\Log\LoggerInterface::class => create(
        \FalconBaseServices\Services\Logger\MonoLog::class,
    ),
];
