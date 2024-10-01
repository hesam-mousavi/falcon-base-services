<?php

namespace FalconBaseServices\Services\TemplateEngine\Implements;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;
use FalconBaseServices\Services\TemplateEngine\Template;

class Twig implements Template
{

    private $twig;

    private $share;

    private $view_dir;

    private string $view;

    /**
     * @var \Twig\Loader\FilesystemLoader
     */
    private FilesystemLoader $loader;

    public function __construct()
    {
        $this->loader = new FilesystemLoader(BASE_SERVICE_PLUGIN_VIEWS_DIR);
    }

    public function setViewDir(string $str)
    {
        $this->view_dir = $str;

        return $this;
    }

    public function setView(string $str)
    {
        $this->view = $str;

        return $this;
    }

    public function share(array $arr)
    {
        $this->share = $arr;

        return $this;
    }

    public function render()
    {
        $this->twig = new Environment($this->loader, [
            'cache' => BASE_SERVICE_PLUGIN_CACHE_DIR,
            'debug' => true,
        ]);
        $this->twig->addExtension(new DebugExtension());
        $this->loader->addPath($this->view_dir);

        return $this->twig->load($this->view.'.twig')->render($this->share);
    }

}
