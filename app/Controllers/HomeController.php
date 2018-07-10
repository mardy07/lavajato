<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 07/04/2018
 * Time: 02:05
 */

namespace App\Controllers;


class HomeController extends Controller
{

    public function index($request, $response)
    {
        return $this->view->render($response, 'home/home.twig');
    }
}
