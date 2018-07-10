<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 24/05/2018
 * Time: 16:41
 */

namespace App\Middleware;


use Respect\Validation\Validator as v;

class ValidationErrosMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if(isset($_SESSION['errors'])) {
            $this->view->getEnvironment()->addGlobal('errors', $_SESSION['errors']);
            unset($_SESSION['errors']);
        }

        $response = $next($request, $response);
        return $response;
    }
}
