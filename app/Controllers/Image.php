<?php

namespace App;

use App\Core\MainController;
use App\Models\File;

class Image extends MainController
{
    public function DefaultPage($data = null)
    {
        $this->Add();
    }

    public function Add()
    {
        if ($_SESSION['authorized'] === true) {
            $this->view->twigLoad('image', []);
        } else {
            //$this->view->twigLoad('denid', []);
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
        }
    }

    public function Download()
    {
        if ($_SESSION['authorized'] === true) {
            try
            {
                $file = $_FILES['filename'];
                $path = '\\download\\'.$_SESSION['id'].' - '.$_SESSION['login'].'\\';
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filetype = explode('.', $file['name']);
                if (in_array($filetype[count($filetype)-1], $allowed)) {
                    if(preg_match('/jpeg/',$file['name']) or preg_match('/jpg/',$file['name']) or preg_match('/png/',$file['name']) or preg_match('/gif/',$file['name']))
                    { //Проверяем имя файла
                        if(preg_match('/jpeg/',$file['type']) or preg_match('/jpg/',$file['type']) or preg_match('/png/',$file['type']) or preg_match('/gif/',$file['type']))
                        {
                            if(file_exists($path)) {
                                move_uploaded_file($file['tmp_name'], PUBLIC_PATH . $path . $file['name']);
                            } else {
                                mkdir($path,0777,true);
                                move_uploaded_file($file['tmp_name'], PUBLIC_PATH . $path . $file['name']);
                            }
                            File::Store($_SESSION['id'],$path . $file['name']);
                            $this->Add();
                        }
                    }
                    else {
                        throw new \Exception("The file type does not match the MIME code");
                    }

                } else {
                    throw new \Exception("Only '*.jpg', '*.jpeg', '*.png', '*.gif' files can be downloaded");
                }
            } catch (\Exception $e) {
                require APPLICATION_PATH."errors/404.php";
            }
        } else {
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
        }
    }

    protected function clearAll($data)
    {
        $data = strip_tags($data);
        $data = htmlspecialchars($data, ENT_QUOTES);
        return $data;
    }
}
