<?php

namespace App;

use App\Core\MainController;
use App\Models\File;
use App\Models\User;
use GUMP;
use Faker;

class Register extends MainController
{
    public function DefaultPage() {
        $this->ShowPage();
    }

    public function ShowPage() {
        if ($_SESSION['authorized'] === true) {
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
        } else {
            $this->view->twigLoad('reg',[]);
        }
    }

    public function Reg() {
        try
        {
            $data = [];
            if ($_POST['password'] === $_POST['password2']) {
                $data = [
                    'login' => $_POST['login'],
                    'password' => $_POST['password']
                ];
            } else {
                throw new \Exception('Incorrect confirm password');
            }
            $valid = GUMP::is_valid($data, [
                'login' => 'required|alpha_numeric',
                'password' => 'required|max_len,100|min_len,6'
            ]);

            if ($valid === true) {
                //Добавляем пользователя со случайными данными и картинками и авторизуемся

                $faker = Faker\Factory::create();

                $source = array(
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'login' => $data['login'],
                    'password' => crypt($data['password'],'a938sA83q0Re04u'),
                    'age' => rand(10,100),
                    'description' => $faker->text,
                    'avatar' => $faker->imageUrl($width = 200, $height = 200, 'people')
                );

                $result = User::Store($source);
                if(count($result)) {
                    session_start();
                    $_SESSION['authorized'] = true;
                    $_SESSION['user'] = $result['name'];
                    $_SESSION['id'] = $result['id'];
                    $_SESSION['login'] = $result['login'];
                } else {
                    throw new \Exception("User not created");
                }
                for($i=0;$i<5;$i++)
                {
                    $user_id = $result['id'];
                    $filename = $faker->imageUrl($width = 640, $height = 480);
                    $userfile = File::Store($user_id, $filename);
                }

                header('Location: http://'.$_SERVER['HTTP_HOST'].'/main/auth');
            } else {
                throw new \Exception("Incorrect register data");
            }

        } catch (\Exception $e){
            require APPLICATION_PATH."errors/404.php";
        }
    }

}