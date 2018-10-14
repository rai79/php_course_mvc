<?php

namespace App\Models;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model;

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

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

class User extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $table = "users";
    protected $primaryKey = 'id';

    static public function Login ($data) {
        $user = self::where('login', $data['login'])->get();
        if ($user[0]->password == crypt($data['password'],'a938sA83q0Re04u')) {
            return array('name' => $user[0]->name, 'id' => $user[0]->id, 'login' => $user[0]->login);
        } else {
            return false;
        }
    }

    static public function Store ($source) {
        $data = [
            'name' => $source['name'],
            'password' => $source['password'],
            'login' => $source['login'],
            'email' => $source['email'],
            'age' => $source['age'],
            'description' => $source['description'],
            'avatar' => $source['avatar']
        ];
        $user = self::create($data);
        return array('name' => $user->name, 'id' =>$user->id, 'login' => $user->login);
    }

    static public function UpdateUser ($data) {
        $user = self::find($data['id']);

        $user->name = $data['name'];
        $user->password = $data['password'];
        $user->login = $data['login'];
        $user->email = $data['email'];
        $user->age = $data['age'];
        $user->description = $data['description'];

        $updated = $user->save();
        return $updated;
    }

    static public function ShowAll($sort_desc = false) {
        $users = [];
        if ($sort_desc) {
            $users = self::all()->sortByDesc('name');
        } else {
            $users = self::all()->sortBy('name');
        }
        $user_list = [];
        foreach ($users as $user) {
            $user_list[] = array(
                'id' => $user->id,
                'login' => $user->login,
                'password' => $user->password,
                'email' => $user->email,
                'name' => $user->name,
                'age' => $user->age,
                'description' => $user->description,
                'avatar' => $user->avatar
            );
        }
        return $user_list;
    }

    static public function ShowByAge($sort_desc = false) {
        $users = [];
        if ($sort_desc) {
            $users = self::all()->sortByDesc('age');
        } else {
            $users = self::all()->sortBy('age');
        }
        $user_list = [];
        foreach ($users as $user) {
            $user_list[] = array(
                'id' => $user->id,
                'login' => $user->login,
                'password' => $user->password,
                'email' => $user->email,
                'name' => $user->name,
                'age' => $user->age,
                'description' => $user->description,
                'avatar' => $user->avatar
            );
        }
        return $user_list;
    }

    static public function DeleteById($id) {
        if ($user = self::find($id)) {
            $user->delete();
            File::where('user_id', $id)->delete();
        } else {
            return false;
        }
        
        return true;
    }

    static public function ShowById($id) {
        $user = self::find($id);
        $user_info[] = array(
            'id' => $user->id,
            'login' => $user->login,
            'password' => $user->password,
            'email' => $user->email,
            'name' => $user->name,
            'age' => $user->age,
            'description' => $user->description,
            'avatar' => $user->avatar
        );
        return $user_info;
    }

}