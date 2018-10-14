<?php

namespace App;

use App\Core\MainController;
use App\Models\File;

class Files extends MainController
{
    public function DefaultPage()
    {
        $this->All();
    }

    public function All()
    {
        if ($_SESSION['authorized'] === true) {
            $data['files'] = File::ShowAll();
            $this->view->twigLoad('files', array('data' => $data));
        } else {
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
        }
    }

    public function Delete($id) {
        if ($_SESSION['authorized'] === true) {
            File::DeleteById($id);
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/files/all');
        } else {
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
        }
    }
}
