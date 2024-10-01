<?php

namespace FalconBaseServices\Services\Sender\Contracts;

interface Email
{
    public function send($to, $subject, $content, $from = null, array $bcc = null): bool;
}
