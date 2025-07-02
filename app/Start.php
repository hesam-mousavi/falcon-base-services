<?php

namespace FalconBaseServices;

class Start
{
    public function run(): void
    {
        $routes = new Routes();
        $routes->register();
    }
}
