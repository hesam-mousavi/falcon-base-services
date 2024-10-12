<?php

namespace FalconBaseServices;

class Start
{
    public function __construct()
    {
        add_action('init', [$this, 'init']);
    }

    public function init(): void
    {
        new Routes();
        $this->actions();
        $this->filters();
    }

    public function actions() {}

    public function filters() {}

}
