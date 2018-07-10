<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 24/05/2018
 * Time: 19:44
 */

namespace App\Auth;


use App\Models\Users;

class Auth
{
    public function user()
    {
        if (isset($_SESSION[PREFIX . 'user'])) {
            $user = new Users();
            return $user->getUser($_SESSION[PREFIX . 'user']);
        }
    }

    public function check()
    {
        return isset($_SESSION[PREFIX . 'user']);
    }

    public function attempt($email, $password)
    {
        $user = Users::where('email', $email)->first();
        if (!$user) {
            return false;
        }
        if (password_verify($password, $user->password)) {
            $_SESSION[PREFIX . 'user'] = $user->id;
            return true;
        }

        return false;
    }

    public function logout()
    {
        unset($_SESSION[PREFIX . 'user']);
    }
}
