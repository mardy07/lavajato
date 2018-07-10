<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 04/07/2018
 * Time: 22:17
 */

namespace App\Controllers;


use App\Models\Customers;
use Respect\Validation\Validator as v;

class CustomersController extends Controller
{
    public function index($request, $response)
    {
        $this->view->render($response, 'customers/customers.twig');
    }

    /**
     * Formulário para criação e modificação de clientes
     *
     * @param $request
     * @param $response
     */
    public function formCustomer($request, $response)
    {
        $id = $request->getAttribute('id');
        $vars['action'] = $this->router->pathFor('customers') .'create';
        $vars['breadcrumb'] = 'Adicionar';

        if($id) {
            $customer = new Customers();
            $vars['customer'] = $customer->where('id', $id)->first();
            $vars['action'] = $this->router->pathFor('customers') .'update';
            $vars['breadcrumb'] = 'Editar';
        }

        $this->view->render($response, 'customers/customer_form.twig', $vars);
    }

    /**
     * Cadastrar cliente no sitema
     *
     * @param $request
     * @param $response
     * @return mixed
     */
    public function create($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'customer' => v::notEmpty(),
            'date_birth' => v::optional(v::date('d/m/Y')),
            'sex' => v::optional(v::alpha()),
            'cellphone' => v::optional(v::phone()),
            'phone' => v::optional(v::phone()),
            'address' => v::optional(v::alnum(', . - ã á â é ê í ó ô ç')),
            'number' => v::optional(v::numeric()),
            'complement' => v::optional(v::alnum(', . - ã á â é ê í ó ô ç')),
            'neighborhood' => v::optional(v::alnum(', . - ã á â é ê í ó ô ç')),
            'city' => v::optional(v::alpha('ã á â é ê í ó ô ç')),
            'state' => v::optional(v::alpha()),
            'zipcode' => v::optional(v::postalCode('BR')),
            'email' => v::optional(v::email()),
        ]);

        if($validation->failed()) {

            if ($request->getParam('return') == 'json') {
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200)
                    ->withJson($validation, NULL, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
            }

            return $response->withRedirect($this->router->pathFor('customers') . 'add');

        }

        $post = $request->getParsedBody();
        $customer = new Customers();
        $customer->customer = ucwords($post['customer']);
        $customer->date_birth = !empty($post['date_birth']) ? $this->dataFormat($post['date_birth']) : NULL;
        $customer->sex = $post['sex'] ?? NULL;
        $customer->cellphone = $post['cellphone'];
        $customer->phone = $post['phone'];
        $customer->address = ucwords($post['address']);
        $customer->number = !empty($post['number']) ? $post['number'] : NULL;
        $customer->complement = $post['complement'];
        $customer->neighborhood = $post['neighborhood'];
        $customer->city = ucwords($post['city']);
        $customer->state = $post['state'];
        $customer->zipcode = $post['zipcode'];
        $customer->email = $post['email'];
        if($customer->save()) {
            $this->flash->addMessage('success', 'Cliente Salvo com sucesso');
        } else {
            $this->flash->addMessage('error', 'Erro ao salvar o Cliente');
        }

        if($request->getParam('return')  == 'json') {
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200)
                ->withJson($customer, NULL, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
        }

        return $response->withRedirect($this->router->pathFor('customers'));

    }

    /**
     * Retorna um Json com todos os clientes cadastrados no sistema
     *
     * @param $request
     * @param $response
     * @return json
     */
    public function read($request, $response)
    {
        $id = $request->getAttribute('id');

        $customers = new Customers();
        if($id) {
            $data = $customers->where('id', $id)->first();
        } else {
            $data = $customers->get();
        }

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200)
            ->withJson(['customers' => $data], NULL, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
    }

    /**
     * Editar dados do cliente
     *
     * @param $request
     * @param $response
     * @return mixed
     */
    public function update($request, $response)
    {
        $post = $request->getParsedBody();

        $validation = $this->validator->validate($request, [
            'id' => v::notEmpty()->numeric(),
            'customer' => v::notEmpty(),
            'date_birth' => v::optional(v::date('d/m/Y')),
            'sex' => v::optional(v::alpha()),
            'cellphone' => v::optional(v::phone()),
            'phone' => v::optional(v::phone()),
            'address' => v::optional(v::alnum(', . - ã á â é ê í ó ô ç')),
            'number' => v::optional(v::numeric()),
            'complement' => v::optional(v::alnum(', . - ã á â é ê í ó ô ç')),
            'neighborhood' => v::optional(v::alnum(', . - ã á â é ê í ó ô ç')),
            'city' => v::optional(v::alpha(', . - ã á â é ê í ó ô ç')),
            'state' => v::optional(v::alpha('ã á â é ê í ó ô ç')),
            'zipcode' => v::optional(v::postalCode('BR')),
            'email' => v::optional(v::email()),
        ]);

        if($validation->failed()) {

            if ($request->getParam('return') == 'json') {
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200)
                    ->withJson($validation, NULL, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
            }

            return $response->withRedirect($this->router->pathFor('customers') . 'edit/'. $post['id']);
        }

        $data = [
            'customer' => ucwords($post['customer']),
            'date_birth' => !empty($post['date_birth']) ? $this->dataFormat($post['date_birth']) : NULL,
            'sex' => $post['sex'],
            'cellphone' => $post['cellphone'],
            'phone' => $post['phone'],
            'address' => ucwords($post['address']),
            'number' => !empty($post['number']) ? $post['number'] : NULL,
            'complement' => $post['complement'],
            'neighborhood' => $post['neighborhood'],
            'city' => ucwords($post['city']),
            'state' => $post['state'],
            'zipcode' => $post['zipcode'],
            'email' => $post['email']
        ];

        $customers = new Customers();
        $customer = $customers->where('id', $post['id']);
        if($customer->update($data)) {
            $this->flash->addMessage('success', 'Cliente Salvo com sucesso');
        } else {
            $this->flash->addMessage('error', 'Erro ao salvar o Cliente');
        }

        if($request->getParam('return')  == 'json') {
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200)
                ->withJson(['customer' => $customer], NULL, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
        }

        return $response->withRedirect($this->router->pathFor('customers'));

    }

    /**
     * Deletar um cliente no sistema
     *
     * @param $request
     * @param $response
     * @return Json
     */
    public function delete($request, $response)
    {
        $id = $request->getAttribute('id');

        $customers = new Customers();
        $customer = $customers->where('id', $id);
        if($customer->delete()) {
            $this->flash->addMessage('success', 'Cliente excluido com sucesso');
        } else {
            $this->flash->addMessage('error', 'Erro ao excluir cliente');
        }

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200)
            ->withJson($this->flash->getMessages(), NULL, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
    }

    /**
     * Converte o formato de data BR para formato MYSQL
     * @param $data
     * @return string|date
     */
    private function dataFormat($data)
    {
        $dt = explode('/',$data);
        return date('Y-m-d H:m:s', strtotime($dt[2].'-'.$dt[1].'-'.$dt[0].' 00:00:00'));
    }

}
