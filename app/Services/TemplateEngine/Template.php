<?php

namespace FalconBaseServices\Services\TemplateEngine;

interface Template
{
    
    public function setViewDir(string $str);
    
    public function setView(string $str);
    
    public function share(string|array $var);
    
    public function render();
    
}
