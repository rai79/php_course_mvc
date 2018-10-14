<?php
namespace App\Core;

use Symfony\Component\Dotenv\Dotenv;

class Config
{
    public function __construct()
    {
        $dotenv = new Dotenv();
        $dotenv->load(APPLICATION_PATH . '/../.env');
        define('MODE', 'prod'); //or dev
    }
}