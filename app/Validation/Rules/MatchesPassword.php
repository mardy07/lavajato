<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 27/05/2018
 * Time: 22:54
 */

namespace App\Validation\Rules;


use Respect\Validation\Rules\AbstractRule;

class MatchesPassword extends AbstractRule
{
    protected $password;

    public function __construct($password)
    {
        $this->password = $password;
    }

    public function validate($input)
    {
        return password_verify($input, $this->password);
    }
}
