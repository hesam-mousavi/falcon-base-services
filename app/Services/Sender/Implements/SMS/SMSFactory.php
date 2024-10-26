<?php

namespace FalconBaseServices\Services\Sender\Implements\SMS;

use FalconBaseServices\Services\Sender\Implements\SMS\Iran\KavehNegar;

class SMSFactory
{
    public function make($sender)
    {
        if ($sender == 'kaveh_negar') {
            return new KavehNegar();
        }
    }
}