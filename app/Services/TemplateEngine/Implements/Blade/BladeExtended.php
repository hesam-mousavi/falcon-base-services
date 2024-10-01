<?php

namespace FalconBaseServices\Services\TemplateEngine\Implements\Blade;

use eftec\bladeone\BladeOne;

class BladeExtended extends BladeOne
{

    public function setViewDir(string $view_dir)
    {
        $this->templatePath = [$view_dir];
        return $this;
    }

}
