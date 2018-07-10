<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 26/05/2018
 * Time: 08:53
 */

namespace App\Controllers\Auth;


use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class PasswordController extends Controller
{
    public function getChangePassword($request, $response)
    {
        return $this->view->render('auth/password/change.twig');
    }

    public function postChangePassword($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'password_old' => v::noWhitespace()->notEmpty()->matchesPassword($this->auth->user()->password),
            'password' => v::noWhitespace()->notEmpty()
        ]);

        if($validation->failed()) {
            $this->flash->addMessage('error', 'Erro na validação');
            return $response->withRedirect($this->router->pathFor('profile'));
        }

        $this->auth->user()->setPassword($request->getParam('password'));

        $this->flash->addMessage('success', 'Sua senha foi alterada');

        return $response->withRedirect($this->router->pathFor('profile'));
    }
}
