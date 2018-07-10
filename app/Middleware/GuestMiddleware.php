<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 27/05/2018
 * Time: 21:42
 */

namespace App\Middleware;


class GuestMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if ($this->auth->check()) {
            return $response->withRedirect($this->router->pathFor('home'));
        }
        $response = $next($request, $response);
        return $response;
    }
}
