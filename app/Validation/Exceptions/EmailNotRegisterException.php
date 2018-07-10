<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 27/05/2018
 * Time: 23:50
 */

namespace App\Validation\Exceptions;


use Respect\Validation\Exceptions\ValidationException;

class EmailNotRegisterException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'Email não cadastrado',
        ],
    ];
}
