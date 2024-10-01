<?php

namespace FalconBaseServices\Services\TemplateEngine\Implements\Blade;

use eftec\bladeone\BladeOne;
use FalconBaseServices\Services\TemplateEngine\Template;

class Blade implements Template
{
    
    private BladeExtended $blade;
    
    public function __construct()
    {
        $this->blade = new BladeExtended(
            BASE_SERVICE_PLUGIN_VIEWS_DIR, BASE_SERVICE_PLUGIN_CACHE_DIR,
            BladeOne::MODE_AUTO
        );
    }

    /**
     * @param  string  $str
     *
     * @return $this
     */
    public function setViewDir(string $str)
    {
        $this->blade->setViewDir($str);
        
        return $this;
    }

    /**
     * @param  string  $str
     *
     * @return $this
     */
    public function setView(string $str)
    {
        $this->blade->setView($str);
        
        return $this;
    }

    /**
     * @param $var
     * @param $value
     *
     * @return $this
     */
    public function share($var, $value = null)
    {
        $this->blade->share($var, $value);
        
        return $this;
    }
    
    public function render(): string
    {
        return $this->blade->run();
    }
    
}
