<?php
require __DIR__."/../../vendor/autoload.php";

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

class User extends Illuminate\Database\Eloquent\Model
{
    protected $guarded = ['id']; //запрещено редактировать только это, все остальное разрешено
    public $timestamps = false;
    protected $table = "users";
    protected $primaryKey = 'id';

}

class UserFile extends Illuminate\Database\Eloquent\Model
{
    protected $guarded = ['id'];
    protected $table = 'userfiles';
    public $timestamps = false;
    protected $primaryKey = 'id';
}

$adminfaker = Faker\Factory::create();
$admin = new User();
$admin->name = 'Aleksey I. Rudel';
$admin->login = 'admin';
$admin->email = 'admin@mail.ru';
$admin->password = 'a9CJ6HTSDXmf.';
$admin->age = 39;
$admin->description = $adminfaker->text;
$admin->avatar = $adminfaker->imageUrl($width = 200, $height = 200, 'people');
$admin->save();

for($i=0;$i<20;$i++)
{
    $faker = Faker\Factory::create();
    $user = new User();
    $user->name = $faker->name;
    $user->login = $faker->userName;
    $user->email = $faker->email;
    $user->password = $faker->password;
    $user->age = rand(10,100);
    $user->description = $faker->text;
    $user->avatar = $faker->imageUrl($width = 200, $height = 200, 'people');
    $user->save();
}

for($i=0;$i<50;$i++)
{
    $faker = Faker\Factory::create();
    $userfile = new UserFile();
    $userfile->user_id = rand(1,20);
    $userfile->filename = $faker->imageUrl($width = 640, $height = 480);
    $userfile->save();
}
