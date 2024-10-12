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
        (new Routes())->register();
        $this->actions();
        $this->filters();
    }

    public function actions() {}

    public function filters() {}

}
