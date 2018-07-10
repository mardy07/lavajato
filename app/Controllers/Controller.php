<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 07/04/2018
 * Time: 00:38
 */

namespace App\Controllers;


class Controller
{
    protected $container;


    public function __construct($container)
    {
        $this->container = $container;
    }

    public function __get($property)
    {
        if($this->container->{$property}){
            return $this->container->{$property};
        }
    }
}
