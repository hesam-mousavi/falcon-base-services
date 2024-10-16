<?php

namespace FalconBaseServices;

class Start
{
    public function run(): void
    {
        add_action('init', [$this, 'init']);
    }

    public function init(): void
    {
        $this->routes();
        $this->actions();
        $this->filters();
    }

    public function routes(): void
    {
        $routes = new Routes();
        $routes->register();
    }

    public function actions() {}

    public function filters() {}

}
