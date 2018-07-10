<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 04/07/2018
 * Time: 19:40
 */

namespace App\Controllers;


use App\Models\ServicesCategories;
use Respect\Validation\Validator as v;

class ServicesCatController extends Controller
{
    public function index($request, $response)
    {
        $categories = new ServicesCategories();
        $vars['categories'] = $categories->get();
        $this->view->render($response, 'services/categories.twig', $vars);
    }

    public function formCategory($request, $response)
    {
        $id = $request->getAttribute('id');
        $vars['action'] = $this->router->pathFor('services') . 'categories/create';
        $vars['breadcrumb'] = 'Adicionar';

        if($id) {
            $vars['action'] = $this->router->pathFor('services') . 'categories/update';
            $vars['breadcrumb'] = 'Editar';
            $categories = new ServicesCategories();
            $vars['category'] = $categories->where('id', $id)->first();
        }

        $this->view->render($response, 'services/category_add.twig', $vars);
    }

    public function create($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'category' => v::notEmpty()->alnum(),
            'value' => v::numeric()->notEmpty(),
            'active' => v::optional(v::numeric())
        ]);

        if($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('services.categories') .'/add');
        }

        $post = $request->getParsedBody();
        $categories = new ServicesCategories();
        $categories->category = strtoupper($post['category']);
        $categories->value = $post['value'];
        $categories->active = $post['active'];
        if($categories->save()) {
            $this->flash->addMessage('success', 'Categoria salva com sucesso');
            return $response->withRedirect($this->router->pathFor('services.categories'));
        }
    }

    public function update($request, $response)
    {
        $post = $request->getParsedBody();

        $validation = $this->validator->validate($request, [
            'id' => v::notEmpty()->numeric(),
            'category' => v::notEmpty()->alnum(),
            'value' => v::numeric()->notEmpty(),
            'active' => v::optional(v::numeric())
        ]);

        if($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('services.categories') . '/edit/' . $post['id']);
        }

        $data = [
            'category' => strtoupper($post['category']),
            'value' => $post['value'],
            'active' => $post['active']
        ];

        $categories = new ServicesCategories();
        $category = $categories->where('id', $post['id']);
        if($category->update($data)) {
            $this->flash->addMessage('success', 'Categoria alterada com sucesso');
        } else {
            $this->flash->addMessage('error', 'Error na alteração');
            return $response->withRedirect($this->router->pathFor('services.categories') . '/edit/' . $post['id'] );
        }

        return $response->withRedirect($this->router->pathFor('services.categories'));
    }

    public function delete($request, $response)
    {
        $id = $request->getAttribute('id');
        $categories = new ServicesCategories();
        $category = $categories->where('id', $id);

        if($category->delete()) {
            $this->flash->addMessage('success', 'Categoria excluida com sucesso');
        } else {
            $this->flash->addMessage('error', 'Erro ao excluida categoria');
        }

        return $response->withRedirect($this->router->pathFor('services.categories'));
    }

}
