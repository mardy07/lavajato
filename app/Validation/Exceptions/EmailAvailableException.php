<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 27/05/2018
 * Time: 22:17
 */

namespace App\Validation\Exceptions;


use Respect\Validation\Exceptions\ValidationException;

class EmailAvailableException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'Email já esta sendo utilizado',
        ],
    ];
}
