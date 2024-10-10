<?php

namespace FalconBaseServices;

class Start
{
    public function __construct()
    {
        new Routes();
        $this->actionHandler();
        $this->filterHandler();
    }

    public function actionHandler() {}

    public function filterHandler() {}

}
