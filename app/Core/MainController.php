<?php
namespace App\Core;

use App\Core\View;

class MainController
{
    protected $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function defaultPage()
    {
        if ($_SESSION['authorized'] === true) {
            $this->view->twigLoad('auth', array('user' => $_SESSION['user']));
        } else {
            $this->view->twigLoad('login',[]);
        }
        //$this->view->twigLoad('login',[]);
    }
}