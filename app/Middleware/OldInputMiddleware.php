<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 24/05/2018
 * Time: 18:41
 */

namespace App\Middleware;


class OldInputMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if (isset($_SESSION['old'])) {
            $this->view->getEnvironment()->addGlobal('old', $_SESSION['old']);
        }
        $_SESSION['old'] = $request->getParams();

        $response = $next($request, $response);
        return $response;
    }
}