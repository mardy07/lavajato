<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 22/07/2018
 * Time: 08:52
 */

namespace App\Controllers;


use App\Models\ExpensesCategories;
use Respect\Validation\Validator as v;

class ExpensesCatController extends Controller
{
    public function index($request, $response)
    {
        $categories = new ExpensesCategories();
        $vars['categories'] = $categories->get();
        $this->view->render($response, 'expenses/categories.twig', $vars);
    }

    public function formCategory($request, $response)
    {
        $id = $request->getAttribute('id');
        $vars['action'] = $this->router->pathFor('expenses') . 'categories/create';
        $vars['breadcrumb'] = 'Adicionar';

        if($id) {
            $vars['action'] = $this->router->pathFor('expenses') . 'categories/update';
            $vars['breadcrumb'] = 'Editar';
            $categories = new ExpensesCategories();
            $vars['category'] = $categories->where('id', $id)->first();
        }

        $this->view->render($response, 'expenses/category_form.twig', $vars);
    }

    public function create($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'category' => v::notEmpty()->alnum('á â é ê í ó ô ç Á Â É Ê Í Ó Ô Ç'),
            'active' => v::optional(v::numeric())
        ]);

        if($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('expenses.categories') .'/add');
        }

        $post = $request->getParsedBody();
        $categories = new ExpensesCategories();
        $categories->category = strtoupper($post['category']);
        $categories->active = $post['active'];
        if($categories->save()) {
            $this->flash->addMessage('success', 'Categoria salva com sucesso');
            return $response->withRedirect($this->router->pathFor('expenses.categories'));
        }
    }

    public function update($request, $response)
    {
        $post = $request->getParsedBody();

        $validation = $this->validator->validate($request, [
            'id' => v::notEmpty()->numeric(),
            'category' => v::notEmpty()->alnum('á â é ê í ó ô ç Á Â É Ê Í Ó Ô Ç'),
            'active' => v::optional(v::numeric())
        ]);

        if($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('expenses.categories') . '/edit/' . $post['id']);
        }

        $data = [
            'category' => strtoupper($post['category']),
            'active' => $post['active']
        ];

        $categories = new ExpensesCategories();
        $category = $categories->where('id', $post['id']);
        if($category->update($data)) {
            $this->flash->addMessage('success', 'Categoria alterada com sucesso');
        } else {
            $this->flash->addMessage('error', 'Error na alteração');
            return $response->withRedirect($this->router->pathFor('expenses.categories') . '/edit/' . $post['id'] );
        }

        return $response->withRedirect($this->router->pathFor('expenses.categories'));
    }

    public function delete($request, $response)
    {
        $id = $request->getAttribute('id');
        $categories = new ExpensesCategories();
        $category = $categories->where('id', $id);

        if($category->delete()) {
            $this->flash->addMessage('success', 'Categoria excluida com sucesso');
        } else {
            $this->flash->addMessage('error', 'Erro ao excluida categoria');
        }

        return $response->withRedirect($this->router->pathFor('expenses.categories'));
    }
}
