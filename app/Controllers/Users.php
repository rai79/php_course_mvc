<?php

namespace App;

use App\Core\MainController;
use App\Models\File;
use App\Models\User;


class Users extends MainController
{
    public function DefaultPage($data = null)
    {
        $this->All();
    }

    public function All()
    {
        if ($_SESSION['authorized'] === true) {
            $data['users'] = User::ShowAll();
            $this->view->twigLoad('users', array('data' => $data));
        } else {
            //$this->view->twigLoad('denid', []);
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
        }
    }

    public function Ageasc()
    {
        if ($_SESSION['authorized'] === true) {
            $data['users'] = User::ShowByAge();
            $this->view->twigLoad('users', array('data' => $data));
        } else {
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
        }
    }

    public function Agedesc()
    {
        if ($_SESSION['authorized'] === true) {
            $data['users'] = User::ShowByAge(true);
            $this->view->twigLoad('users', array('data' => $data));
        } else {
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
        }
    }

    public function Nameasc()
    {
        $this->All();
    }

    public function Namedesc()
    {
        if ($_SESSION['authorized'] === true) {
            $data['users'] = User::ShowAll(true);
            $this->view->twigLoad('users', array('data' => $data));
        } else {
            //$this->view->twigLoad('denid', []);
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
        }
    }

    public function Userinfo()
    {
        if ($_SESSION['authorized'] === true) {
            $data['users'] = User::ShowById(true);
            $this->view->twigLoad('userinfo', array('data' => $data));
        } else {
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
        }
    }

}
