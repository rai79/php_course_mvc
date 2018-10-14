<?php

namespace App;

use App\Core\MainController;
use App\Models\User;
use GUMP;

class Admin extends MainController
{
    public function DefaultPage()
    {
        $this->All();
    }

    public function All()
    {
        if (($_SESSION['authorized'] === true) & ($_SESSION['login'] === 'admin')) {
            $data['users'] = User::ShowAll();
            $this->view->twigLoad('admin', array('data' => $data));
        } else {
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
        }
    }

    public function Ageasc()
    {
        if (($_SESSION['authorized'] === true) & ($_SESSION['login'] === 'admin')) {
            $data['users'] = User::ShowByAge();
            $this->view->twigLoad('admin', array('data' => $data));
        } else {
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
        }
    }

    public function Agedesc()
    {
        if (($_SESSION['authorized'] === true) & ($_SESSION['login'] === 'admin')) {
            $data['users'] = User::ShowByAge(true);
            $this->view->twigLoad('admin', array('data' => $data));
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
        if (($_SESSION['authorized'] === true) & ($_SESSION['login'] === 'admin')) {
            $data['users'] = User::ShowAll(true);
            $this->view->twigLoad('admin', array('data' => $data));
        } else {
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
        }
    }

    public function Delete($id) {
        if (($_SESSION['authorized'] === true) & ($_SESSION['login'] === 'admin')) {
            User::DeleteById($id);
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/admin');
        } else {
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
        }
    }

    public function Edit($id) {
        if (($_SESSION['authorized'] === true) & ($_SESSION['login'] === 'admin')) {
            $data ['users'] = User::ShowById($id);
            $this->view->twigLoad('edituser', array('data' => $data));
        } else {
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
        }
    }

    public function Add() {
        if (($_SESSION['authorized'] === true) & ($_SESSION['login'] === 'admin')) {
            $this->view->twigLoad('adduser', []);
        } else {
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
        }
    }

    public function Update($id) {
        if (($_SESSION['authorized'] === true) & ($_SESSION['login'] === 'admin')) {

            $data = [
                'id' => $id,
                'name' => $_POST['name'],
                'login' => $_POST['login'],
                'password' => crypt($_POST['password'],'a938sA83q0Re04u'),
                'age' => $_POST['age'],
                'description' => $_POST['description'],
                'email' => $_POST['email']
            ];

            try
            {
                $valid = GUMP::is_valid($data, [
                    'id' => 'required|numeric',
                    'name' => 'required|max_len,200|min_len,6',
                    'login' => 'required|max_len,200|min_len,6',
                    'password' => 'required|max_len,100|min_len,6',
                    'age' => 'required|numeric',
                    'email' => 'required|valid_email'
                ]);

                if ($valid === true) {
                    $result = User::UpdateUser($data);
                    if($result) {
                        header('Location: http://'.$_SERVER['HTTP_HOST'].'/admin');
                    } else {
                        throw new \Exception("Update error");
                    }
                } else {
                    throw new \Exception("Incorrect incoming data");
                }

            } catch (\Exception $e){
                require APPLICATION_PATH."errors/404.php";
            }
        } else {
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
        }
    }

    public function Create() {
        if (($_SESSION['authorized'] === true) & ($_SESSION['login'] === 'admin')) {

            $data = [
                'name' => $_POST['name'],
                'login' => $_POST['login'],
                'password' => crypt($_POST['password'],'a938sA83q0Re04u'),
                'age' => $_POST['age'],
                'description' => $_POST['description'],
                'avatar' => "",
                'email' => $_POST['email']
            ];

            try
            {
                $valid = GUMP::is_valid($data, [
                    'name' => 'required|max_len,200|min_len,6',
                    'login' => 'required|max_len,200|min_len,6',
                    'password' => 'required|max_len,100|min_len,6',
                    'age' => 'required|numeric',
                    'email' => 'required|valid_email'
                ]);

                if ($valid === true) {
                    $result = User::Store($data);
                    if(count($result)) {
                        header('Location: http://'.$_SERVER['HTTP_HOST'].'/admin');
                    } else {
                        throw new \Exception("Create error");
                    }
                } else {
                    throw new \Exception("Incorrect incoming data");
                }

            } catch (\Exception $e){
                require APPLICATION_PATH."errors/404.php";
            }
        } else {
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
        }
    }
}