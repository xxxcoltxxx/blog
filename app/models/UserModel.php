<?php

use Data\Handlers\Validate;
use Data\Providers\DB;

class UserModel extends \System\Model
{
    public $id;
    public $email;
    public $password;
    public $name;
    public $created_at;
    public $updated_at;

    protected static $table = "users";

    public function getDisplayName()
    {
        return $this->name ?: $this->email;
    }

    public static function get($id)
    {
        Validate::int($id, 1, null, "Пользователь не найден");

        $db = DB::instance();
        $query = $db->getQuery();
        $query
            ->select("*")
            ->from(self::$table)
            ->where("id = '%d'", $id);
        $user = $db->queryRow($query);

        Validate::check(!empty($user), "Пользователь не найден");
        return new self($user);
    }

    public static function authorize($email, $password)
    {
        Validate::length($email, 1, null, "Заполните поле Email");
        Validate::length($password, 1, null, "Заполните поле Пароль");

        $db = DB::instance();
        $query = $db->getQuery();
        $query
            ->select("*")
            ->from(self::$table)
            ->where("email = '%s'", $email)
            ->where("password = '%s'", md5($password));
        $user = $db->queryRow($query);

        Validate::check(!empty($user), "Неверный логин или пароль");

        $_SESSION['user']['id'] = $user['id'];
        return new self($user);
    }

    public static function isAuthorized()
    {
        if (isset($_SESSION['user']['id'])) {
            return self::get($_SESSION['user']['id']);
        } else {
            return false;
        }
    }
}