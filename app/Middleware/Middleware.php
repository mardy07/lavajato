<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 24/05/2018
 * Time: 17:07
 */

namespace App\Middleware;


class Middleware
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function __get($property)
    {
        if($this->container->{$property}) {
            return $this->container->{$property};
        }
    }
}
