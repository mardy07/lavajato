<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 21/05/2018
 * Time: 22:05
 */

namespace App\Controllers\Auth;


use App\Controllers\Controller;
use Respect\Validation\Validator as v;

final class AuthController extends Controller
{

    public function login($request, $response)
    {
        $vars['title'] = 'Login';
        $this->view->render($response, 'auth/login.twig', $vars);
    }

    public function logar($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'email' => v::noWhitespace()->notEmpty()->email()->emailNotRegister(),
            'password' => v::noWhitespace()->notEmpty()
        ]);

        if ($validation->failed()) {

            return $response->withRedirect($this->router->pathFor('auth.login'));

        }

        $auth = $this->auth->attempt(
            $request->getParam('email'),
            $request->getParam('password')
        );

        if (!$auth) {

            $this->flash->addMessage('error', 'Senha incorreta');
            return $response->withRedirect($this->router->pathFor('auth.login'));

        }

        $this->flash->addMessage('success', 'Seja bem vindo!');
        return $response->withRedirect($this->router->pathFor('home'));

    }

    public function logout($request, $response)
    {
        $this->auth->logout();
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }

}
