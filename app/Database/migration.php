<?php
require __DIR__."/../../vendor/autoload.php";

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__."/../../.env");

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => getenv('DB_HOST'),
    'database'  => getenv('DB_NAME'),
    'username'  => getenv("DB_ADMIN"),
    'password'  => getenv('DB_PASSWORD'),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();

$capsule->bootEloquent();

Capsule::schema()->dropIfExists('users');

Capsule::schema()->create('users', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name'); //varchar 255
    $table->string('login'); //varchar 255
    $table->string('email')->unique(); //varchar 255
    $table->string('password'); //varchar 255
    $table->integer('age');
    $table->string('description', 1000);
    $table->string('avatar', 500);
});

Capsule::schema()->dropIfExists('userfiles');

Capsule::schema()->create('userfiles', function (Blueprint $table) {
    $table->increments('id');
    $table->integer('user_id');
    $table->string('filename', 500);
});
