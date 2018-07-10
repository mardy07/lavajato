<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 04/07/2018
 * Time: 12:46
 */

namespace App\Controllers;


use App\Models\Customers;
use App\Models\Services;
use App\Models\ServicesCategories;
use Respect\Validation\Validator as v;
use DateTime;

class ServicesController extends Controller
{
    public function index($request, $response)
    {
        $this->view->render($response, 'services/services.twig');
    }

    public function formService($request, $response)
    {
        $id = $request->getAttribute('id');

        $category = new ServicesCategories();
        $vars['category'] = $category->get();
        $customers = new Customers();
        $vars['customers'] = $customers->select('id', 'customer')->get();
        $vars['action'] = $this->router->pathFor('services') . 'create';
        $vars['breadcrumb'] = 'Adicionar';

        if($id) {
            $vars['action'] = $this->router->pathFor('services') . 'update';
            $vars['breadcrumb'] = 'Editar';
            $services = new Services();
            $vars['service'] = $services->where('id', $id)->first();
        }

        $this->view->render($response, 'services/service_add.twig', $vars);
    }

    public function create($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'service' => v::notEmpty()->numeric(),
            'date' => v::date('d/m/Y'),
            'placa' => v::alnum('-'),
            'model' => v::notEmpty(),
            'color' => v::optional(v::alpha()),
            'customer_id' => v::optional(v::numeric()),
            'cellphone' => v::optional(v::digit('- ( ) .')),
            'value' => v::numeric(),
            'start_time' => v::optional(v::digit(':')),
            'end_time' => v::optional(v::digit(':')),
            'comments' => v::optional(v::alnum('! . , : % á â é ê í ó ô ç'))
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('services.form'));
        }

        $post = $request->getParsedBody();

        $customers = new Customers();
        $customer = $customers->where('id', $post['customer_id'])->first();

        $service = new Services();
        $service->category_id = $post['service'];
        $service->date = $this->dataFormat($post['date']);
        $service->placa = strtoupper($post['placa']);
        $service->model = strtoupper($post['model']);
        $service->color = $post['color'];
        $service->customer_id = $customer->id ?? NULL;
        $service->cellphone = $post['cellphone'];
        $service->value = isset($post['value']) && empty($post['value']) ? 0 : $post['value'];
        $service->start_time = $post['start_time'];
        $service->end_time = $post['end_time'];
        $service->payment_status = $post['payment'] ?? NULL;
        $service->status = $post['status'];
        $service->comments = $post['comments'];
        if ($service->save()) {
            $this->flash->addMessage('success', 'Serviço salvo com sucesso');
        } else {
            $this->flash->addMessage('error', 'Erro ao salvar serviço');
            return $response->withRedirect($this->router->pathFor('services.form'));
        }

        return $response->withRedirect($this->router->pathFor('services'));

    }

    public function read($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'dt_start' => v::optional(v::date('d-m-Y')),
            'dt_end' => v::optional(v::date('d-m-Y'))
        ]);
        if ($validation->failed()) {
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404)
                ->withJson(['errors' => $validation->errors], NULL, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
        }

        $get = $request->getParams();
        $dt_start = isset($get['dt_start']) && !empty($get['dt_start']) ? DateTime::createFromFormat('d-m-Y', $get['dt_start'])->format('Y-m-d') : NULL;
        $dt_end = isset($get['dt_end']) && !empty($get['dt_end'])? DateTime::createFromFormat('d-m-Y', $get['dt_end'])->format('Y-m-d') : NULL;

        $services = new Services();
        $data = $services->getAllServices($dt_start, $dt_end);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200)
            ->withJson(['services' => $data], NULL, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
    }

    public function update($request, $response)
    {
        $post = $request->getParsedBody();

        $validation = $this->validator->validate($request, [
            'id' => v::notEmpty()->numeric(),
            'service' => v::notEmpty()->numeric(),
            'date' => v::date('d/m/Y'),
            'placa' => v::alnum('-'),
            'model' => v::notEmpty(),
            'color' => v::optional(v::alpha()),
            'customer_id' => v::optional(v::numeric()),
            'cellphone' => v::optional(v::digit('- ( ) .')),
            'value' => v::numeric(),
            'start_time' => v::optional(v::digit(':')),
            'end_time' => v::optional(v::digit(':')),
            'comments' => v::optional(v::alnum('! . , : % á â é ê í ó ô ç'))
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('services') . '/edit/' . $post['id'] );
        }

        $customers = new Customers();
        $customer = $customers->where('id', $post['customer_id'])->first();

        $data = [
            'category_id' => $post['service'],
            'date' => $this->dataFormat($post['date']),
            'placa' => strtoupper($post['placa']),
            'model' => strtoupper($post['model']),
            'color' => $post['color'],
            'customer_id' => $customer->id ?? NULL,
            'cellphone' => $post['cellphone'],
            'value' => isset($post['value']) && empty($post['value']) ? 0 : $post['value'],
            'start_time' => $post['start_time'],
            'end_time' => $post['end_time'],
            'payment_status' => $post['payment'] ?? NULL,
            'status' => $post['status'],
            'comments' => $post['comments']
        ];

        $services = new Services();
        $service = $services->where('id', $post['id']);
        if ($service->update($data)) {
            $this->flash->addMessage('success', 'Serviço salvo com sucesso');
        } else {
            $this->flash->addMessage('error', 'Erro ao salvar serviço');
            return $response->withRedirect($this->router->pathFor('services') . '/edit/'. $post['id'] );
        }

        return $response->withRedirect($this->router->pathFor('services'));

    }

    public function delete($request, $response)
    {
        $id = $request->getAttribute('id');

        $services = new Services();
        $service = $services->where('id', $id);
        if($service->delete()) {
            $this->flash->addMessage('success', 'Cliente excluido com sucesso');
        } else {
            $this->flash->addMessage('error', 'Erro ao excluir cliente');
        }

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200)
            ->withJson($this->flash->getMessages(), NULL, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
    }

    public function getService($request, $response)
    {
        $id = $request->getAttribute('id');
        $validation = v::intVal()->validate($id);

        if ($validation == false) {
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404)
                ->withJson(['errors' => 'id deve ser um número inteiro'], NULL, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
        }

        $services = new Services();
        $data = $services->getService($id);

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200)
            ->withJson(['service' => $data], NULL, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
    }

    /**
     * Converte o formato de data BR para formato MYSQL
     * @param $data
     * @return string|date
     */
    private function dataFormat($data)
    {
        $dt = explode('/', $data);
        return date('Y-m-d H:m:s', strtotime($dt[2] . '-' . $dt[1] . '-' . $dt[0] . ' 00:00:00'));
    }

}
