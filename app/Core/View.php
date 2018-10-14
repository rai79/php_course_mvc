<?php

namespace App\Core;

use Twig_Environment;

class View
{
    protected $loader;
    protected $twig;

    public function __construct()
    {
        $this->loader = new \Twig_Loader_Filesystem(APPLICATION_PATH . 'Views');
        $this->twig = new Twig_Environment($this->loader);
    }

    public function twigLoad(String $filename, array $data)
    {
        echo $this->twig->render(strtolower($filename) . ".twig", $data);
    }

}
