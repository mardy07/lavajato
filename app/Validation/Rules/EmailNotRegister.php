<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 27/05/2018
 * Time: 23:47
 */

namespace App\Validation\Rules;


use App\Models\Users;
use Respect\Validation\Rules\AbstractRule;

class EmailNotRegister extends AbstractRule
{
    public function validate($input)
    {
        return Users::where('email',$input)->count() !== 0;
    }
}
