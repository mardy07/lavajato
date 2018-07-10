<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 23/05/2018
 * Time: 20:50
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'user_img',
        'user_name',
        'password',
        'active',
        'user_level',
        'email',
    ];
    protected $hidden = ['password'];

    public function setPassword($password)
    {
        $this->update([
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);
    }

    public function getUser($id)
    {
        $user = Users::find($id);
        return $user;
    }

    public function setUserImg($user_img)
    {
        $this->update([
            'user_img' => $user_img,
        ]);
        return true;
    }
}
