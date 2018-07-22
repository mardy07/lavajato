<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 22/07/2018
 * Time: 08:27
 */

namespace App\Controllers;


use App\Models\Expenses;
use App\Models\ExpensesCategories;
use Respect\Validation\Validator as v;
use DateTime;

class ExpensesController extends Controller
{
    public function index($request, $response)
    {
        $this->view->render($response, 'expenses/expenses.twig');
    }

    public function formExpenses($request, $response)
    {
        $id = $request->getAttribute('id');
        $category = new ExpensesCategories();
        $vars['category'] = $category::all();
        $vars['action'] = $this->router->pathFor('expenses') . 'create';
        $vars['breadcrumb'] = 'Adicionar';
        if($id) {
            $vars['action'] = $this->router->pathFor('expenses') . 'update';
            $vars['breadcrumb'] = 'Editar';
            $expenses = new Expenses();
            $vars['expense'] = $expenses->where('id', $id)->first();
        }
        $this->view->render($response, 'expenses/expenses_form.twig', $vars);
    }

    public function create($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'category_id' => v::numeric(),
            'expense' => v::notEmpty()->alnum('- á â é ê í ó ô ç Á Â É Ê Í Ó Ô Ç'),
            'value' => v::digit('. ,'),
            'expiration_date' => v::date('d/m/Y'),
            'payment_date' => v::optional(v::date('d/m/Y')),
            'payment_status' => v::optional(v::numeric())
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('expenses.form'));
        }

        $post = $request->getParsedBody();

        $expense = new Expenses();
        $expense->category_id = $post['category_id'];
        $expense->expense = $post['expense'];
        $expense->value = isset($post['value']) && !empty($post['value']) ? $post['value'] : 0;
        $expense->expiration_date = $this->dataFormat($post['expiration_date']);
        $expense->payment_date = $post['payment_date'] ? $this->dataFormat($post['payment_date']) : NULL;
        $expense->payment_status = $post['payment_status'] ?? NULL;

        if ($expense->save()) {
            $this->flash->addMessage('success', 'Despesa registrada com sucesso');
        } else {
            $this->flash->addMessage('error', 'Erro ao cadastrar despesa');
            return $response->withRedirect($this->router->pathFor('expenses.form'));
        }

        return $response->withRedirect($this->router->pathFor('expenses'));

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

        $expenses = new Expenses();
        $data = $expenses->getAllExpenses($dt_start, $dt_end);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200)
            ->withJson(['expenses' => $data], NULL, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
    }

    public function update($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'category_id' => v::numeric(),
            'expense' => v::notEmpty()->alnum('- á â é ê í ó ô ç Á Â É Ê Í Ó Ô Ç'),
            'value' => v::digit('. ,'),
            'expiration_date' => v::date('d/m/Y'),
            'payment_date' => v::optional(v::date('d/m/Y')),
            'payment_status' => v::optional(v::numeric())
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('expenses.form'));
        }

        $post = $request->getParsedBody();

        $data = [
            'category_id' => $post['category_id'],
            'expense' => $post['expense'],
            'value' => isset($post['value']) && !empty($post['value']) ? $post['value'] : 0,
            'expiration_date' => $this->dataFormat($post['expiration_date']),
            'payment_date' => $post['payment_date'] ? $this->dataFormat($post['payment_date']) : NULL,
            'payment_status' => $post['payment_status'] ?? NULL,
        ];

        $expenses = new Expenses();
        $expense = $expenses->where('id', $post['id']);
        if ($expense->update($data)) {
            $this->flash->addMessage('success', 'Despesa alterada com sucesso');
        } else {
            $this->flash->addMessage('error', 'Erro ao editar despesa');
            return $response->withRedirect($this->router->pathFor('expenses.form'));
        }

        return $response->withRedirect($this->router->pathFor('expenses'));

    }

    public function delete($request, $response)
    {
        $id = $request->getAttribute('id');

        $expenses = new Expenses();
        $expense = $expenses->where('id', $id);
        if($expense->delete()) {
            $this->flash->addMessage('success', 'Despesa excluido com sucesso');
        } else {
            $this->flash->addMessage('error', 'Erro ao excluir despesa');
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
        if($data) {
            $dt = explode('/', $data);
            return date('Y-m-d H:m:s', strtotime($dt[2] . '-' . $dt[1] . '-' . $dt[0] . ' 00:00:00'));
        }
        return NULL;
    }

    public function getExpense($request, $response)
    {
        $id = $request->getAttribute('id');
        $validation = v::intVal()->validate($id);

        if ($validation == false) {
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404)
                ->withJson(['errors' => 'id deve ser um número inteiro'], NULL, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
        }

        $services = new Expenses();
        $data = $services->getExpense($id);

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200)
            ->withJson(['expense' => $data], NULL, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
    }

}
