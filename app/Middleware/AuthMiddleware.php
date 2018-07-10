<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 24/05/2018
 * Time: 16:34
 */

namespace App\Middleware;


class AuthMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if (!$this->auth->check()) {
            $this->flash->addMessage('error', 'Por favor, faça o login');
            return $response->withRedirect($this->router->pathFor('auth.login'));
        }
        $response = $next($request, $response);
        return $response;
    }
}
