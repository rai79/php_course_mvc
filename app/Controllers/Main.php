<?php

namespace App;

use App\Core\MainController;
use App\Models\User;
use GUMP;

class Main extends MainController
{
    public function Auth()
    {
        if ($_SESSION['authorized'] === true) {
            $this->view->twigLoad('auth', array('user' => $_SESSION['user']));
        } else {
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/main/login');
        }
    }

    public function Logout() {
        if ($_SESSION['authorized'] === true) {
            session_destroy();
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
        }
    }

    public function Login() {

        $data = [
            'login' => $_POST['login'],
            'password' => $_POST['password']
        ];

        try
        {
            $valid = GUMP::is_valid($data, [
                'login' => 'required|alpha_numeric',
                'password' => 'required|max_len,100|min_len,6'
            ]);

            if ($valid === true) {
                $result = User::Login($data);
                if(count($result)) {
                    session_start();
                    $_SESSION['authorized'] = true;
                    $_SESSION['user'] = $result['name'];
                    $_SESSION['id'] = $result['id'];
                    $_SESSION['login'] = $result['login'];
                    header('Location: http://'.$_SERVER['HTTP_HOST'].'/main/auth');
                }
            } else {
                throw new \Exception("Incorrect login data");
            }

        } catch (\Exception $e){
            require APPLICATION_PATH."errors/404.php";
        }

    }

}
