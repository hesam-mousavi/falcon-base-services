<?php

namespace FalconBaseServices\Services\Sender\Contracts;

interface SMS
{
    public function send(string $receptor, string $message): bool;
}
